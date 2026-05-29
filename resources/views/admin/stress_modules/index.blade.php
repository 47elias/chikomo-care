@extends('layouts.master')
<title>Stress Modules Management - Chikomo Care</title>

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
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            {{-- Form Column Left Frame --}}
            <div class="col-md-4">
                <div class="box box-primary shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-upload text-primary"></i> Upload New PDF</h3>
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
                                <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf" required>
                                <p class="help-block">Must be a valid PDF format file document. (Max size: 10MB)</p>
                                @error('pdf_file') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-block" style="font-weight: 600;"><i class="fa fa-cloud-upload"></i> Complete Upload File Pipeline</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table Ledger Data Column Right Frame --}}
            <div class="col-md-8">
                <div class="box box-default shadow-sm" style="border-radius: 4px;">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight: 600;"><i class="fa fa-file-pdf-o text-danger"></i> Active Resource Library</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr style="background: #f9fafb;">
                                        <th style="padding: 12px 8px;">Title</th>
                                        <th style="padding: 12px 8px;">Description</th>
                                        <th style="padding: 12px 8px;">Uploaded On</th>
                                        <th style="padding: 12px 8px; width: 140px;" class="text-center">Action Parameters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($modules) > 0)
                                        @foreach($modules as $mod)
                                            <tr>
                                                <td style="font-weight: 600; padding: 12px 8px;"><i class="fa fa-file-pdf-o text-red"></i> {{ $mod->title }}</td>
                                                <td style="color: #555; padding: 12px 8px;">{{ $mod->description ?? 'No tracking summary provided.' }}</td>
                                                <td style="color: #777; padding: 12px 8px;">{{ $mod->created_at->format('Y-m-d H:i') }}</td>
                                                <td style="padding: 12px 8px;" class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ asset('storage/' . $mod->file_path) }}" target="_blank" class="btn btn-xs btn-default" title="View PDF Document"><i class="fa fa-eye text-info"></i></a>
                                                        <form action="{{ route('stress-modules.destroy', $mod->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Confirm permanent deletion of this file element?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-default" title="Delete Resource Element"><i class="fa fa-trash text-danger"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-muted" style="padding: 32px;">
                                                <i class="fa fa-folder-open-o fa-2x d-block mb-2"></i><br>
                                                No PDF modules configured. Use the form tool on the left to upload documentation assets.
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
@endsection
