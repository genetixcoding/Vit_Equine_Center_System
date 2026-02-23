@extends('layouts.admin')
<title>My Notes Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">My Note Page</h4>
                    </div>
                </div>
                 <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">My Notes
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        @foreach ($notes as $note)
                                        <thead class="text-center">
                                            <th colspan="2" style="background-color: #338ded; color: #FFF;">
                                                {{ $note->description }}
                                            </th>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td>
                                                    {{ $note->created_at->format('d/m/Y h:i A') }}
                                                </td>
                                                <td>
                                                    @if ($note->user)
                                                        {{ $note->user->name }}
                                                    @else
                                                        Unknown User
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                        @endforeach
                                        <div class="pages text-center">
                                            {{ $notes->links() }}
                                        </div>
                                    </table>
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
