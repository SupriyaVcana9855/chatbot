@extends('layout.app')
@section('content')

<div class="boxpadding">
    <div class="card">
        <div class="card-body" style="
    padding: 50px;
">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="boxinner">
                            <h1>{{ $agent->exists ? 'Edit Agent Details' : 'Add New Agent' }}</h1>
                            <form action="{{ route('saveAgent') }}" method="post">
                                @csrf
                                <input type="hidden" name="agent_id" value="{{ $agent->id }}">

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $agent->name) }}" placeholder="Enter name">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $agent->email) }}" placeholder="Enter email">
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="number" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $agent->phone_number) }}" placeholder="Enter phone number">
                                    @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">{{ $agent->exists ? 'Update' : 'Submit' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection