<?php

namespace App\Models;

use App\Benefit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use PDF;

class Bill extends Model implements HasMedia
{

    use HasMediaTrait;

    protected $fillable = ['client_id', 'designation'];

    protected $appends = ['total_price'];
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function benefits()
    {
        return $this->belongsToMany(Benefit::class)->withPivot('unit_price', 'currency', 'quantity');
    }

    public function getTotalPriceAttribute()
    {
        $benefits = collect($this->benefits)->map(function ($b) {
            return ['currency' => $b->pivot->currency, 'price' => $b->pivot->unit_price * $b->pivot->quantity];
        })->groupBy(function ($b) {
            return $b['currency'];
        })->map(function ($bs) {
            return $bs->sum(function ($b) {
                return $b['price'];
            });
        });

        return $benefits->all();
    }

    public function savePDF()
    {
        $name = sprintf('facture-%s.pdf', str_slug($this->designation));
        $dir  = sprintf('bills/%s', str_slug($this->client->company_name));
        if (!is_dir($dir)) {
            Storage::makeDirectory($dir);
        }
        $path = storage_path(sprintf('app/%s/%s', $dir, $name));
        PDF::loadView('bills.pdf-export', ['bill' => $this])->save($path);
        $folderId     = $this->client->folder_id;
        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'            => $name,
            'parents'         => [$folderId],
            'writersCanShare' => true,
        ]);

        $content                = file_get_contents($path);
        $file                   = app()->make(\Google_Service_Drive::class)->files->create($fileMetadata, [
            'data'     => $content,
            'mimeType' => 'application/pdf',
            'fields'   => 'id,webViewLink',
        ]);
        $this->gd_file_id       = $file->id;
        $this->gd_web_view_link = $file->webViewLink;
        $this->save();
    }

    public function paid(Carbon $paidAt = null)
    {
        if (!$paidAt) {
            $paidAt = Carbon::now();
        }
        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'description' => sprintf('Payée le %s', $paidAt->format("d/m/Y")),
            'starred'     => true
        ]);
        app()->make(\Google_Service_Drive::class)->files->update($this->gd_file_id, $fileMetadata);
        $this->paid_at = $paidAt;
        $this->save();
    }
}
