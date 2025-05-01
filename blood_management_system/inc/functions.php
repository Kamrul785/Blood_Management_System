<?php
// inc/functions.php

/**
 * Fetch all hospitals for dropdown
 * @param PDO $pdo
 * @return array
 */
function getHospitals(PDO $pdo) {
  $stmt = $pdo->query('SELECT hospital_id, name FROM hospitals ORDER BY name');
  return $stmt->fetchAll();
}
/**
 * Fetch all events (latest first)
 */
function getEvents(PDO $pdo) {
  $sql = "SELECT event_id AS id, title, message, date, hospital_id
          FROM events
          ORDER BY date DESC";
  return $pdo->query($sql)->fetchAll();
}

/**
* Fetch blood-inventory quantities keyed by blood type
*/
function getInventory(PDO $pdo) {
  $sql = "SELECT blood_type, quantity FROM inventory";
  $rows = $pdo->query($sql)->fetchAll();
  // map to [ 'A+' => 10, ... ]
  $inv = [];
  foreach ($rows as $r) {
      $inv[ $r['blood_type'] ] = $r['quantity'];
  }
  return $inv;
}