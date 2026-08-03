<?php
class Setting {
    private $conn;
    private $table = 'settings';

    public $id;
    public $company_name;
    public $company_logo;
    public $office_start_time;
    public $office_end_time;
    public $auto_checkout_time;
    public $late_time;
    public $working_hours;
    public $weekday_ot_rate;
    public $weekend_ot_rate;
    public $holiday_ot_rate;
    public $max_ot_hours;
    public $annual_leave_limit;
    public $casual_leave_limit;
    public $medical_leave_limit;
    public $paid_leave_limit;
    public $unpaid_leave_rules;
    public $half_day_leave_rules;
    public $absent_deduction_rate;
    public $half_day_deduction_rate;
    public $late_deduction_rules;
    public $excess_paid_leave_deduction_rules;
    public $custom_deduction_rules;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getSettings() {
        $query = "SELECT * FROM " . $this->table . " LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET 
                    company_name=:company_name, 
                    office_start_time=:office_start_time, 
                    office_end_time=:office_end_time,
                    auto_checkout_time=:auto_checkout_time,
                    late_time=:late_time,
                    working_hours=:working_hours,
                    weekday_ot_rate=:weekday_ot_rate,
                    weekend_ot_rate=:weekend_ot_rate,
                    holiday_ot_rate=:holiday_ot_rate,
                    max_ot_hours=:max_ot_hours,
                    annual_leave_limit=:annual_leave_limit,
                    casual_leave_limit=:casual_leave_limit,
                    medical_leave_limit=:medical_leave_limit,
                    paid_leave_limit=:paid_leave_limit,
                    unpaid_leave_rules=:unpaid_leave_rules,
                    half_day_leave_rules=:half_day_leave_rules,
                    absent_deduction_rate=:absent_deduction_rate,
                    half_day_deduction_rate=:half_day_deduction_rate,
                    late_deduction_rules=:late_deduction_rules,
                    excess_paid_leave_deduction_rules=:excess_paid_leave_deduction_rules,
                    custom_deduction_rules=:custom_deduction_rules
                  WHERE id=1";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":company_name", $this->company_name);
        $stmt->bindParam(":office_start_time", $this->office_start_time);
        $stmt->bindParam(":office_end_time", $this->office_end_time);
        $stmt->bindParam(":auto_checkout_time", $this->auto_checkout_time);
        $stmt->bindParam(":late_time", $this->late_time);
        $stmt->bindParam(":working_hours", $this->working_hours);
        $stmt->bindParam(":weekday_ot_rate", $this->weekday_ot_rate);
        $stmt->bindParam(":weekend_ot_rate", $this->weekend_ot_rate);
        $stmt->bindParam(":holiday_ot_rate", $this->holiday_ot_rate);
        $stmt->bindParam(":max_ot_hours", $this->max_ot_hours);
        $stmt->bindParam(":annual_leave_limit", $this->annual_leave_limit);
        $stmt->bindParam(":casual_leave_limit", $this->casual_leave_limit);
        $stmt->bindParam(":medical_leave_limit", $this->medical_leave_limit);
        $stmt->bindParam(":paid_leave_limit", $this->paid_leave_limit);
        $stmt->bindParam(":unpaid_leave_rules", $this->unpaid_leave_rules);
        $stmt->bindParam(":half_day_leave_rules", $this->half_day_leave_rules);
        $stmt->bindParam(":absent_deduction_rate", $this->absent_deduction_rate);
        $stmt->bindParam(":half_day_deduction_rate", $this->half_day_deduction_rate);
        $stmt->bindParam(":late_deduction_rules", $this->late_deduction_rules);
        $stmt->bindParam(":excess_paid_leave_deduction_rules", $this->excess_paid_leave_deduction_rules);
        $stmt->bindParam(":custom_deduction_rules", $this->custom_deduction_rules);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
