@extends('layouts.master')

@section('content-wrapper')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Stress Relief Modules
            <small>Admin PDF Asset Repository</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Stress Modules</li>
        </ol>
    </section>

    <section class="content">
        {{-- Flash Messaging Feedback Stream --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible modern-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            {{-- Form Column Left Frame --}}
            <div class="col-md-4">
                <div class="box box-primary dashboard-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-upload text-primary"></i> Upload New PDF</h3>
                    </div>
                    <!-- Form explicitly opens file payload stream encodings -->
                    <form action="{{ route('stress-modules.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group @error('title') has-error @enderror">
                                <label for="title">Module Document Title</label>
                                <input type="text" name="title" class="form-control" id="title" placeholder="e.g., Managing Academic Anxiety" value="{{ old('title') }}" required>
                                @error('title') <span class="help-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group @error('description') has-error @enderror">
                                <label for="description">Brief Summary / Overview</label>
                                <textarea name="description" class="form-control" id="description" rows="4" placeholder="Describe what this resource covers...">{{ old('description') }}</textarea>
                                @error('description') <span class="help-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group @error('pdf_file') has-error @enderror">
                                <label for="pdf_file">Select PDF Document</label>
                                <div class="file-upload-wrap">
                                    <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" required class="file-input-modern">
                                </div>
                                <p class="help-block upload-hint"><i class="fa fa-info-circle"></i> Must be a valid PDF format file document. (Max size: 10MB)</p>
                                @error('pdf_file') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-block action-btn"><i class="fa fa-cloud-upload"></i> Complete Upload File Pipeline</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table Ledger Data Column Right Frame --}}
            <div class="col-md-8">
                <div class="box box-default dashboard-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file-pdf-o text-danger"></i> Active Resource Library</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover modern-table mb-0">
                                <thead>
                                    <tr class="table-head-row">
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Uploaded On</th>
                                        <th class="text-center actions-col-sm">Action Parameters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($modules) > 0)
                                        @foreach($modules as $mod)
                                            <tr>
                                                <td class="title-cell"><i class="fa fa-file-pdf-o text-red"></i> {{ $mod->title }}</td>
                                                <td class="desc-cell">{{ $mod->description ?? 'No tracking summary provided.' }}</td>
                                                <td class="date-cell">{{ $mod->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ asset('storage/' . $mod->file_path) }}" target="_blank" class="btn btn-xs btn-default table-action-btn" title="View PDF Document"><i class="fa fa-eye text-info"></i></a>
                                                        <form action="{{ route('stress-modules.destroy', $mod->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Confirm permanent deletion of this file element?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-default table-action-btn" title="Delete Resource Element"><i class="fa fa-trash text-danger"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center empty-state">
                                                <i class="fa fa-folder-open-o fa-2x"></i>
                                                <div>No PDF modules configured. Use the form tool on the left to upload documentation assets.</div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .modern-alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .dashboard-box {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    .dashboard-box .box-header {
        padding: 16px 18px;
    }

    .dashboard-box .box-title {
        font-weight: 600;
    }

    /* File upload */
    .file-upload-wrap {
        border: 1.5px dashed #cbd5e1;
        border-radius: 8px;
        padding: 16px;
        background: #f9fafb;
        transition: border-color 0.15s ease, background 0.15s ease;
    }

    .file-upload-wrap:hover {
        border-color: #3c8dbc;
        background: #f5f9fc;
    }

    .file-input-modern {
        width: 100%;
    }

    .upload-hint {
        margin-top: 8px;
        color: #94a3b8;
    }

    .upload-hint i {
        margin-right: 4px;
    }

    .action-btn {
        border-radius: 6px;
        font-weight: 600;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }

    /* Table */
    .modern-table {
        margin-bottom: 0;
    }

    .table-head-row th {
        background: #f9fafb;
        padding: 12px 10px;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 700;
        border-bottom: 2px solid #eef1f4 !important;
    }

    .modern-table td {
        padding: 12px 10px;
        vertical-align: middle;
    }

    .title-cell {
        font-weight: 600;
        color: #1e293b;
    }

    .title-cell i {
        margin-right: 4px;
    }

    .desc-cell {
        color: #64748b;
        font-size: 13px;
    }

    .date-cell {
        color: #94a3b8;
        font-size: 12.5px;
        font-family: 'SFMono-Regular', Consolas, monospace;
    }

    .actions-col-sm {
        width: 140px;
    }

    .inline-form {
        display: inline-block;
    }

    .table-action-btn {
        border-radius: 4px;
        transition: background 0.15s ease;
    }

    .table-action-btn:hover {
        background: #f1f5f9;
    }

    .empty-state {
        padding: 32px;
        color: #94a3b8;
    }

    .empty-state i {
        display: block;
        margin-bottom: 8px;
        opacity: 0.6;
    }
</style>
@endsection
