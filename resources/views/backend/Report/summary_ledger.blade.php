@extends('layout.main')
@section('title') {{ 'Summary Ledger' }} @endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Summary Ledger</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Accounting Ledger Summary</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-center" colspan="2">Ledger Book</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-danger">Debit (Left Side)</th>
                                        <th class="text-center text-success">Credit (Right Side)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>All Total Purchases</span>
                                                <strong>{{ number_format((float) $totalPurchases, 2) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>All Total Sales</span>
                                                <strong>{{ number_format((float) $totalSales, 2) }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>Current Total Stock Value</span>
                                                <strong>{{ number_format((float) $currentStockValue, 2) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>Gross Profit</span>
                                                <strong>{{ number_format((float) $grossProfit, 2) }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>All Expenses</span>
                                                <strong>{{ number_format((float) $totalExpenses, 2) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>Total Net Profit</span>
                                                <strong>{{ number_format((float) $netProfit, 2) }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>Cash Shortage Loss</span>
                                                <strong>{{ number_format((float) $cashShortageLoss, 2) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>Cash Overage Gain</span>
                                                <strong>{{ number_format((float) $cashOverageGain, 2) }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="d-flex justify-content-between">
                                                <span>Cash Net Adjustment Impact</span>
                                                <strong>{{ number_format((float) $cashNetAdjustment, 2) }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>Stock Shortage Loss</span>
                                                <strong>{{ number_format((float) $stockShortageLoss, 2) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <span>Stock Overage Gain</span>
                                                <strong>{{ number_format((float) $stockOverageGain, 2) }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="d-flex justify-content-between">
                                                <span>Stock Net Adjustment Impact</span>
                                                <strong>{{ number_format((float) $stockNetAdjustment, 2) }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-light border mt-3 mb-0">
                            <strong>Formula:</strong> Net Profit = Gross Profit - All Expenses - Cash Shortage Loss + Cash Overage Gain - Stock Shortage Loss + Stock Overage Gain
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
