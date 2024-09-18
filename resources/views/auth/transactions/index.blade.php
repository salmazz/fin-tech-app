@extends('layouts.master')

@section('title', 'Home')

@push('css')
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            padding: 0;
        }
    </style>
@endpush

@section('content')
    <h1>Transaction History</h1>

    @if ($transactions->isEmpty())
        <p>No transactions found.</p>
    @else
        <table id="transactionsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
            <thead>
            <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Fee</th>
                <th>Recipient</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->type }}</td>
                    <td>${{ $transaction->amount }}</td>
                    <td>
                        {{ $transaction->transaction_fee > 0 ? '$' . $transaction->transaction_fee : '' }}
                    </td>
                    <td>{{ $transaction->recipientWallet->user->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
            </tbody>

        </table>
    @endif
@endsection

@push('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#transactionsTable').DataTable({
                responsive: true,
                paging: true, // Ensure pagination is enabled
                pageLength: 10, // Set the number of rows per page
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print', 'colvis'
                ]
            });
        });
    </script>
@endpush
