@extends('layouts.app')

@section('content')
<form action="upload_file" method="post" enctype="multipart/form-data">
    @csrf
    Select image to upload:
    <input type="file" name="photo" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
@endsection
