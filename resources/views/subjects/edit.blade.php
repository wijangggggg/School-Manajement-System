@extends('layouts.app')
@section('content')
<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4 text-warning">Edit Mata Pelajaran</h5>
        <form action="/subjects/{{ $subject->subject_id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Kode Mapel</label>
                <input type="text" name="subject_code" class="form-control" value="{{ $subject->subject_code }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Mata Pelajaran</label>
                <input type="text" name="subject_name" class="form-control" value="{{ $subject->subject_name }}" required>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="/subjects" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold text-dark">Update Mapel</button>
            </div>
        </form>
    </div>
</div>
@endsection
