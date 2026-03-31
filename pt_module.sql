-- PT Module Migration (Evosapiens Movement)
-- Run this against your `gym` database.

-- 1) Add payment_type column to tbl_revenue (so PT payments can be differentiated)
ALTER TABLE tbl_revenue
  ADD COLUMN payment_type VARCHAR(30) NOT NULL DEFAULT 'MEMBERSHIP';

-- 2) PT Plans
CREATE TABLE IF NOT EXISTS pt_plans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  total_sessions INT NOT NULL DEFAULT 0,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3) PT Assignments (PT Member)
CREATE TABLE IF NOT EXISTS pt_members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id VARCHAR(150) NOT NULL,
  pt_plan_id INT NOT NULL,
  trainer_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  total_sessions INT NOT NULL,
  sessions_used INT NOT NULL DEFAULT 0,
  status VARCHAR(20) NOT NULL DEFAULT 'Active',
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pt_members_plan (pt_plan_id),
  INDEX idx_pt_members_trainer (trainer_id),
  INDEX idx_pt_members_member (member_id)
);

-- 4) PT Sessions (per assignment)
CREATE TABLE IF NOT EXISTS pt_sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pt_member_id INT NOT NULL,
  session_no INT NOT NULL,
  used_on DATETIME NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Pending',
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_pt_member_session (pt_member_id, session_no),
  INDEX idx_pt_sessions_pt_member (pt_member_id),
  INDEX idx_pt_sessions_status (status)
);

