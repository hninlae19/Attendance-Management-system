<?php
class LeaveType {
    private $conn;
    private $table = 'leave_types';

    public $id;
    public $name;
    public $days_allowed;
    public $is_paid;
    public $service_period_months;
    public $gender_restriction;
    public $carry_forward;
    public $attachment_required;
    public $approval_workflow;
    public $is_active;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY is_active DESC, name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                  name=:name, days_allowed=:days_allowed, is_paid=:is_paid,
                  service_period_months=:service_period_months, gender_restriction=:gender_restriction,
                  carry_forward=:carry_forward, attachment_required=:attachment_required,
                  approval_workflow=:approval_workflow, is_active=:is_active";
        $stmt = $this->conn->prepare($query);
        
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->days_allowed = htmlspecialchars(strip_tags($this->days_allowed));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":days_allowed", $this->days_allowed);
        $stmt->bindParam(":is_paid", $this->is_paid, PDO::PARAM_BOOL);
        $stmt->bindParam(":service_period_months", $this->service_period_months);
        $stmt->bindParam(":gender_restriction", $this->gender_restriction);
        $stmt->bindParam(":carry_forward", $this->carry_forward, PDO::PARAM_BOOL);
        $stmt->bindParam(":attachment_required", $this->attachment_required, PDO::PARAM_BOOL);
        $stmt->bindParam(":approval_workflow", $this->approval_workflow);
        $stmt->bindParam(":is_active", $this->is_active, PDO::PARAM_BOOL);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET 
                  name=:name, days_allowed=:days_allowed, is_paid=:is_paid,
                  service_period_months=:service_period_months, gender_restriction=:gender_restriction,
                  carry_forward=:carry_forward, attachment_required=:attachment_required,
                  approval_workflow=:approval_workflow, is_active=:is_active
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->days_allowed = htmlspecialchars(strip_tags($this->days_allowed));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":days_allowed", $this->days_allowed);
        $stmt->bindParam(":is_paid", $this->is_paid, PDO::PARAM_BOOL);
        $stmt->bindParam(":service_period_months", $this->service_period_months);
        $stmt->bindParam(":gender_restriction", $this->gender_restriction);
        $stmt->bindParam(":carry_forward", $this->carry_forward, PDO::PARAM_BOOL);
        $stmt->bindParam(":attachment_required", $this->attachment_required, PDO::PARAM_BOOL);
        $stmt->bindParam(":approval_workflow", $this->approval_workflow);
        $stmt->bindParam(":is_active", $this->is_active, PDO::PARAM_BOOL);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // Soft delete
        $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }
}
