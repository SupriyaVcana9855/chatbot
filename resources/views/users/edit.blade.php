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
                            <h1>Edit User Details</h1>
                            <form action="{{ route('userSave') }}" method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $users[0]->id }}">

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $users[0]->name) }}" placeholder="Enter name">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $users[0]->email) }}" placeholder="Enter email">
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="number" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $users[0]->phone_number) }}" placeholder="Enter phone number">
                                    @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection