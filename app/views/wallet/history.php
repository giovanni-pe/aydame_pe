<!-- app/views/wallet/history.php -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fa fa-clock"></i> Historial de Transacciones</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($transactions)): ?>
                        <div class="empty-state">
                            <i class="fa fa-receipt"></i>
                            <h5>No hay transacciones</h5>
                            <p class="text-muted">Aún no tienes movimientos en tu billetera</p>
                            <a href="<?= BASE_URL ?>wallet/recharge" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Recargar Saldo
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                        <th>Tarea</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i', strtotime($transaction->created_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= getTransactionColor($transaction->type) ?>">
                                                    <i class="fa <?= getTransactionIcon($transaction->type) ?>"></i>
                                                    <?= ucfirst(str_replace('_', ' ', $transaction->type)) ?>
                                                </span>
                                            </td>
                                            <td><?= $transaction->description ?></td>
                                            <td>
                                                <span class="<?= $transaction->amount > 0 ? 'text-success' : 'text-danger' ?>">
                                                    <?= $transaction->amount > 0 ? '+' : '' ?>$<?= number_format($transaction->amount, 2) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($transaction->task_title): ?>
                                                    <small class="text-muted"><?= $transaction->task_title ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">-</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Saldo Actual -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5>Saldo Actual</h5>
                    <h2 class="text-primary">$<?= number_format($user->wallet_balance, 2) ?></h2>
                    <a href="<?= BASE_URL ?>wallet/recharge" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Recargar
                    </a>
                </div>
            </div>
            
            <!-- Resumen -->
            <?php if (!empty($transactions)): ?>
                <div class="card">
                    <div class="card-header">
                        <h6>Resumen del Mes</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        $totalIngresos = 0;
                        $totalGastos = 0;
                        $currentMonth = date('Y-m');
                        
                        foreach ($transactions as $transaction) {
                            if (strpos($transaction->created_at, $currentMonth) === 0) {
                                if ($transaction->amount > 0) {
                                    $totalIngresos += $transaction->amount;
                                } else {
                                    $totalGastos += abs($transaction->amount);
                                }
                            }
                        }
                        ?>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ingresos:</span>
                            <span class="text-success">+$<?= number_format($totalIngresos, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Gastos:</span>
                            <span class="text-danger">-$<?= number_format($totalGastos, 2) ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Balance:</strong>
                            <strong class="<?= ($totalIngresos - $totalGastos) >= 0 ? 'text-success' : 'text-danger' ?>">
                                $<?= number_format($totalIngresos - $totalGastos, 2) ?>
                            </strong>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Acciones -->
            <div class="card mt-4">
                <div class="card-body">
                    <a href="<?= BASE_URL ?>dashboard" class="btn btn-secondary w-100 mb-2">
                        <i class="fa fa-arrow-left"></i> Volver al Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>wallet/recharge" class="btn btn-primary w-100">
                        <i class="fa fa-wallet"></i> Recargar Saldo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function getTransactionColor($type) {
    switch ($type) {
        case 'recarga': return 'primary';
        case 'pago_recibido': return 'success';
        case 'pago_enviado': return 'warning';
        case 'reembolso': return 'info';
        default: return 'secondary';
    }
}

function getTransactionIcon($type) {
    switch ($type) {
        case 'recarga': return 'fa-plus-circle';
        case 'pago_recibido': return 'fa-arrow-down';
        case 'pago_enviado': return 'fa-arrow-up';
        case 'reembolso': return 'fa-undo';
        default: return 'fa-exchange-alt';
    }
}
?>