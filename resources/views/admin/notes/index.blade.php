@extends('layouts.admin')
<title>Notes Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                <h4 class="text-white text-capitalize ps-3">Notes Table</h4>
              </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-link float-end">
                    <a data-bs-toggle="modal" data-bs-target="#exampleModalpharmacy" class="text-sm">Add New Note</a>
                </button>
            </div>
            <div>
                <div class="modal fade" id="exampleModalpharmacy" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-note') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Note Details</h3>

                                        <div class="mb-2 col-12">
                                            <select name="user_id" required class="form-control">
                                                <option value="">Select User</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2 col-12">
                                            <textarea name="description" id="" class="form-control" placeholder="Description"></textarea>
                                        </div>
                                        <div class="m-2">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-1 my-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-lg-6 col-7">
                                    <h6>all Notes</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <div class="dropdown float-lg-end pe-4">
                                        <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v text-secondary"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0 m-0">
                            <div class="px-4 table-responsive">
                                <h5 class="text-primary">Notes By Year & Day</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        @php
                                            $groupedByYear = $notesByDay->groupBy(function($item) {
                                                return \Carbon\Carbon::parse($item->day)->year;
                                            });
                                        @endphp
                                        @foreach($groupedByYear as $year => $days)
                                            <tr>
                                                <th colspan="2" style="background-color: #e3f2fd;">Year: {{ $year }}</th>
                                            </tr>
                                            @foreach($days as $day)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($day->day)->format('d M') }}</td>
                                                    <td>{{ $day->count }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pages text-center">
                                    {{ $notesByDay->links() }}
                                </div>
                            </div>

                            {{-- New: Show all notes attached to each day --}}
                            <div class="mt-4 px-1">
                                <h5 class="text-info">Notes Attached to Each Day</h5>
                                @foreach ($notesGroupedByDay as $day => $notes)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <strong>{{ $day }}</strong>
                                        </div>
                                        <div class="card-body p-2">
                                            <ul class="list-group">
                                                @foreach ($notes as $note)
                                                    <li class="list-group-item">
                                                        <strong>Sender:</strong>
                                                        @if ($note->user_id && $note->manager_id == null)
                                                            <span class="text-primary">{{ $note->user->name }}</span> To <span class="text-primary">Manager</span>
                                                        @else
                                                            <span class="text-primary">Manager</span> To <span class="text-primary">{{ $note->user->name }}</span>
                                                        @endif
                                                        <br>
                                                        <strong>Description:</strong> {{ $note->description }}
                                                        <br>
                                                        <a class="text-primary float-end p-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModall{{ $note->id }}">Edit</a>
                                                        <a class="text-danger float-end p-1 text-sm" href="{{url('delete-note/'.$note->id)}}" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    @foreach ($notes as $note)
                                        <div class="modal fade" id="exampleModall{{ $note->id }}" tabindex="-1" aria-labelledby="exampleModalLabell{{ $note->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="card-body py-5">
                                                        <form action="{{ url('update-notes/'.$note->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row m-2">
                                                                <div class="mb-2 col-12">
                                                                    <select name="user_id" required class="form-control">
                                                                        <option value="">Select User</option>
                                                                        @foreach ($users as $user)
                                                                            <option value="{{ $user->id }}" {{ $user->id == $note->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2 col-12">
                                                                    <textarea name="description" class="form-control" placeholder="Description">{{ $note->description }}</textarea>
                                                                </div>
                                                                <div class="col-md-12 mt-3">
                                                                    <button type="submit" class="btn btn-primary">update</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                                {{-- Pagination links for days --}}
                                <div class="pages text-center">
                                    {{ $days->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
