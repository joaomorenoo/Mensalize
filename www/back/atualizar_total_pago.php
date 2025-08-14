<?php
function atualizarTotalPago($id_evento, $conecta) {
    mysqli_query($conecta, "DELETE FROM total_pago WHERE id_evento = $id_evento");

    $sqlInsert = "
        INSERT INTO total_pago (id_evento, id_turma, total_pagamentos)
        SELECT 
            m.id_evento,
            m.id_turma,
            SUM(p.valor_pago)
        FROM pagamento p
        INNER JOIN mensalidades m ON p.id_mensalidade = m.id_mensalidade
        WHERE m.id_evento = $id_evento
        GROUP BY m.id_evento, m.id_turma
    ";

    return mysqli_query($conecta, $sqlInsert);
}
