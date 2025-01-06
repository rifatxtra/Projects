<?php

function getProperty()
{
  global $pdo;
  $stmt = $pdo->query("SELECT * FROM properties");
  $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Fetch flats for each property
  foreach ($properties as &$property) {
      $flatStmt = $pdo->prepare("SELECT * FROM flats WHERE property_id = :property_id");
      $flatStmt->bindParam(':property_id', $property['id']);
      $flatStmt->execute();
      $property['flats'] = $flatStmt->fetchAll(PDO::FETCH_ASSOC);
  }

  echo json_encode(['status' => true, 'data' => $properties]);
}

function addFlat($data) {
  global $pdo;
  if (isset($data['propertyId'], $data['flatName'], $data['floor'], $data['tenants'])) {
      $propertyId = $data['propertyId'];
      $flatName = $data['flatName'];
      $floor = $data['floor'];
      $rent=$data['rent'];
      $tenants = $data['tenants'];

      // Insert flat into the database
      $stmt = $pdo->prepare("INSERT INTO flats (property_id, flat_name, floor, rent, tenants) VALUES (:propertyid, :flatname, :floor, :rent, :tenants)");
      $stmt->bindParam(':propertyid', $propertyId);
      $stmt->bindParam(':flatname', $flatName);
      $stmt->bindParam(':floor', $floor);
      $stmt->bindParam(":rent",$rent);
      $stmt->bindParam(':tenants', $tenants);
      $stmt->execute();

      echo json_encode(['status' => true, 'message' => 'Flat added successfully.']);
  } else {
      echo json_encode(['status' => false, 'message' => 'Invalid propertyId, flatName, floor, or tenants']);
  }
}

function editFlat($data) {
  global $pdo;
  if (isset($data['id'], $data['flatName'], $data['floor'], $data['tenants'])) {
      $id = $data['id'];
      $flatName = $data['flatName'];
      $floor = $data['floor'];
      $rent = $data['rent'];
      $tenants = $data['tenants'];

      // Update flat in the database
      $stmt = $pdo->prepare("UPDATE flats SET flat_name = :flatname, floor = :floor, rent=:rent, tenants = :tenants WHERE id = :id");
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':flatname', $flatName);
      $stmt->bindParam(':floor', $floor);
      $stmt->bindParam(":rent",$rent);
      $stmt->bindParam(':tenants', $tenants);
      $stmt->execute();

      echo json_encode(['status' => true, 'message' => 'Flat updated successfully.']);
  } else {
      echo json_encode(['status' => false, 'message' => 'Invalid data. Id, flatName, floor, tenants required.']);
  }
}

function deleteFlat($data) {
  global $pdo;
  if (isset($data['id'])) {
      $id = $data['id'];

      // Delete the flat
      $stmt = $pdo->prepare("DELETE FROM flats WHERE id = :id");
      $stmt->bindParam(':id', $id);
      $stmt->execute();

      echo json_encode(['status' => true, 'message' => 'Flat deleted successfully.']);
  } else {
      echo json_encode(['status' => false, 'message' => 'Invalid data. Flat ID required.']);
  }
}

function addproperty($data) {
  global $pdo;
  if (isset($data['name'], $data['location'])) {
      $name = $data['name'];
      $location = $data['location'];

      // Insert property into the database
      $stmt = $pdo->prepare("INSERT INTO properties (name, location) VALUES (:name, :location)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':location', $location);
      $stmt->execute();

      echo json_encode(['status' => true, 'message' => 'Property added successfully.']);
  } else {
      echo json_encode(['status' => false, 'message' => 'Invalid data. Name, location']);
  }
}

function editproperty($data) {
  global $pdo;
  if (isset($data['id'], $data['name'], $data['location'])) {
      $id = $data['id'];
      $name = $data['name'];
      $location = $data['location'];

      // Update property in the database
      $stmt = $pdo->prepare("UPDATE properties SET name = :name, location = :location WHERE id = :id");
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':location', $location);
      $stmt->execute();

      echo json_encode(['status' => true, 'message' => 'Property updated successfully.']);
  } else {
      echo json_encode(['status' => false, 'message' => 'Invalid data. Id, name, location required.']);
  }
}

function deleteproperty($data) {
  global $pdo;
  if (isset($data['id'])) {
      $id = $data['id'];

      // First, delete all flats associated with this property
      $deleteFlatsStmt = $pdo->prepare("DELETE FROM flats WHERE property_id = :id");
      $deleteFlatsStmt->bindParam(':id', $id);
      $deleteFlatsStmt->execute();

      // Then, delete the property
      $deletePropertyStmt = $pdo->prepare("DELETE FROM properties WHERE id = :id");
      $deletePropertyStmt->bindParam(':id', $id);
      $deletePropertyStmt->execute();

      echo json_encode(['status' => true, 'message' => 'Property and associated flats deleted successfully.']);
  } else {
      echo json_encode(['status' => false, 'message' => 'Invalid data. Property ID required.']);
  }
}

