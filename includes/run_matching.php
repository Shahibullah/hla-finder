<?php

function getMatchRequirements($conn, $match_request_id)
{
    $stmt = $conn->prepare(
        "SELECT gene_id, requirement_level
         FROM match_requirements
         WHERE match_request_id = ?"
    );

    $stmt->bind_param("i", $match_request_id);
    $stmt->execute();

    return $stmt->get_result();
}
