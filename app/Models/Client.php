<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use SoftDeletes, Notifiable;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'company_name',
        'siren',
        'address',
        'postal_code',
        'city',
        'country',
        'email',
        'phone_number',
    ];

    /**
     * Get the bills for the client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function getFolderIdAttribute()
    {
        if (is_null($this->gd_folder_id)) {
            $fileMetadata       = new \Google_Service_Drive_DriveFile([
                'name'     => str_slug($this->company_name),
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents'  => [config('filesystems.disks.google.folderId')]
            ]);
            $file               = app()->make(\Google_Service_Drive::class)->files->create($fileMetadata, [
                'fields' => 'id,webViewLink']);
            $this->gd_folder_id = $file->id;
            $this->gd_web_view_link = $file->webViewLink;
            $this->save();
        }

        return $this->gd_folder_id;
    }
}
