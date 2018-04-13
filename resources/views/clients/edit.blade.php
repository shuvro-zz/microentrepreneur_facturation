@extends('layouts.app')

@section('active-menu', 1)

@section('body')
    <el-card class="box-card" header="{{ $client->company_name }}">
        <div class="text item">
            <el-form ref="form" :model="client" method="post" action="{{ route('clients.update', ['id' => $client->id]) }}">
                <input name="_method" type="hidden" value="PUT">
                @csrf
                <el-form-item :error="errors['company_name']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">Raison Social</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input name="company_name" :required="true" v-model="client.company_name"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item :error="errors['siren']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">SIREN</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input name="siren" :required="true" v-model="client.siren"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item :error="errors['address']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">Adresse</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input type="textarea" :required="true" name="address" v-model="client.address"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item :error="errors['postal_code']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">Code Postal</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input name="postal_code" :required="true" v-model="client.postal_code"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item :error="errors['city']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">Ville</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input name="city" :required="true" v-model="client.city"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item :error="errors['country']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">Pays</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input name="country" :required="true" v-model="client.country"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item :error="errors['email']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">Email</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input name="email" :required="true" v-model="client.email"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item :error="errors['phone_number']">
                    <el-row>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            <label class="d-block w-100">Tel</label>
                        </el-col>
                        <el-col :span="24" :sm="{span: 18}">
                            <el-input name="phone_number" :required="true" v-model="client.phone_number"></el-input>
                        </el-col>
                    </el-row>
                </el-form-item>
                <el-form-item>
                    <el-row>
                        <el-col :span="24" :sm="{offset: 10, span: 6}">
                        <el-button native-type="submit" type="primary" class="w-100" >Enregistrer <i class="fas fa-save"></i></el-button>
                        </el-col>
                    </el-row>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
@endsection

@push('scripts')
    <script>
        window.client = {!! json_encode((object) collect($client->toArray())->merge(old())) !!}
    </script>
    <script src="{{ asset('js/default-app.js') }}" defer></script>
@endpush