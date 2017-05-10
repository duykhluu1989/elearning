@extends('backend.layouts.main')

@section('page_heading', 'Setting')

@section('section')

    <div class="box box-default">
        <div class="box-header with-border">
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                @foreach($settings as $setting)
                    <tr>
                        <td>{{ $setting->name }}</td>
                        <td>{{ $setting->code }}</td>
                        <td>{{ $setting->value }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop