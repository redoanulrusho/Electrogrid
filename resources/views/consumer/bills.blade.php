@extends('layouts.consumer')

@section('title', 'ElectroGrid — Billing History')

@section('styles')
<style>
    .bill-table { background: transparent; color: var(--text-hud); }
    .bill-table thead th {
        border-bottom: 1px solid var(--border-neon-teal);
        color: var(--neon-teal);
        font-family: 'Rajdhani', sans-serif;
        text-transform: uppercase;
        font-size: 0.78rem;
        letter-spacing: 1px;
        padding: 12px 16px;
    }
    .bill-table tbody tr {
        background: rgba(14,21,37,0.4);
        border-bottom: 1px solid rgba(0,245,255,0.06);
        transition: all 0.2s;
    }
    .bill-table tbody tr:hover { background: rgba(14,21,37,0.8); }
    .bill-table tbody td { padding: 14px 16px; border: none; font-size: 0.88rem; }
    .unpaid-row { border-left: 3px solid var(--warning-crimson) !important; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="tech-font text-white fw-bold mb-1">⚡ Billing History</h4>
            <p class="text-secondary m-0" style="font-size:0.82rem;">
                Electricity consumption records for Meter:
                <code class="text-info">{{ $user->meter_number }}</code>
                · Class: <span class="text-white">{{ $user->consumer_class }}</span>
            </p>
        </div>
    </div>

    {{-- Unpaid Bill Highlight --}}
    @if($latestUnpaid)
    <div class="cyber-card p-4 mb-4 card-danger" style="border-color:rgba(255,42,84,0.4);background:rgba(255,42,84,0.04);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="tech-font text-danger fw-bold" style="font-size:0.8rem;">⚠ OUTSTANDING BALANCE</span>
                <h3 class="text-white fw-bold mb-0 mt-1">৳{{ number_format($latestUnpaid->amount, 2) }}</h3>
                <p class="text-secondary m-0 mt-1" style="font-size:0.82rem;">
                    For {{ \Carbon\Carbon::parse($latestUnpaid->month)->format('F Y') }}
                    · Due {{ \Carbon\Carbon::parse($latestUnpaid->due_date)->format('d M Y') }}
                    @if(\Carbon\Carbon::parse($latestUnpaid->due_date)->isPast())
                        <span class="text-danger ms-2 fw-bold">OVERDUE</span>
                    @endif
                </p>
            </div>
            <div class="text-end">
                <div class="text-secondary" style="font-size:0.72rem;">UNITS CONSUMED</div>
                <div class="text-warning fw-bold fs-4 tech-font">{{ $latestUnpaid->units_consumed }} kWh</div>
                <div class="text-secondary" style="font-size:0.72rem;">@ BDT {{ $latestUnpaid->rate_applied }}/unit</div>
                @if($latestUnpaid->document_path)
                <div class="mt-2">
                    <a href="{{ asset($latestUnpaid->document_path) }}" target="_blank" class="btn btn-sm btn-outline-info tech-font py-1" style="font-size:0.75rem;">
                        <i class="bi bi-file-earmark-pdf me-1"></i>View Attached Bill Document
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Bills Table --}}
    <div class="cyber-card">
        <div class="cyber-card-header">
            <h6 class="tech-font text-white m-0 fw-bold">
                <i class="bi bi-table me-2 text-info"></i>Monthly Billing Records
            </h6>
        </div>
        <div class="p-3">
            @if($bills->count() > 0)
            <div class="table-responsive">
                <table class="table bill-table m-0">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th class="text-end">Units (kWh)</th>
                            <th class="text-end">Rate (BDT/unit)</th>
                            <th class="text-end">Amount (BDT)</th>
                            <th class="text-center">Due Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Document</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bills as $bill)
                        <tr class="{{ $bill->paid_status === 'unpaid' ? 'unpaid-row' : '' }}">
                            <td class="text-white fw-semibold">{{ \Carbon\Carbon::parse($bill->month)->format('F Y') }}</td>
                            <td class="text-end text-info fw-bold">{{ number_format($bill->units_consumed, 0) }}</td>
                            <td class="text-end text-secondary">{{ $bill->rate_applied }}</td>
                            <td class="text-end fw-bold {{ $bill->paid_status === 'unpaid' ? 'text-warning' : 'text-white' }}">
                                ৳{{ number_format($bill->amount, 2) }}
                            </td>
                            <td class="text-center text-secondary">{{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($bill->paid_status === 'paid')
                                    <span class="badge tech-font" style="background:rgba(0,230,118,0.1);border:1px solid rgba(0,230,118,0.3);color:var(--grid-green);font-size:0.65rem;">PAID</span>
                                @else
                                    <span class="badge tech-font" style="background:rgba(255,42,84,0.1);border:1px solid rgba(255,42,84,0.3);color:var(--warning-crimson);font-size:0.65rem;">UNPAID</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($bill->document_path)
                                    <a href="{{ asset($bill->document_path) }}" target="_blank" class="btn btn-sm btn-cyber-outline tech-font py-0 px-2" style="font-size:0.72rem;border-color:var(--border-neon-teal);">
                                        <i class="bi bi-file-earmark-text text-info me-1"></i>View
                                    </a>
                                @else
                                    <span class="text-secondary" style="font-size:0.7rem;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $bills->links() }}</div>
            @else
            <div class="text-center text-secondary py-5">
                <i class="bi bi-receipt fs-2 mb-2 d-block text-secondary"></i>
                No billing records found yet.
            </div>
            @endif
        </div>
    </div>

    {{-- Rate note --}}
    <div class="mt-4 p-3 rounded" style="background:rgba(0,0,0,0.2);border:1px dashed rgba(0,245,255,0.1);">
        <p class="text-secondary m-0" style="font-size:0.75rem;">
            <i class="bi bi-info-circle me-1 text-info"></i>
            <strong class="text-secondary">Rate Formula:</strong>
            Residential: BDT 6.00/kWh · Commercial: BDT 9.50/kWh ·
            Industrial (tiered): 0–500 kWh @ 9.00 | 500–1000 kWh @ 10.50 | &gt;1000 kWh @ 12.00
        </p>
    </div>

</div>
@endsection
