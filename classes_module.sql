-- Classes Management Migration (Evosapiens Movement)
-- Run this against your `gym` database.

-- 1) Classes
CREATE TABLE IF NOT EXISTS classes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  trainer_id INT NOT NULL,
  capacity INT NOT NULL DEFAULT 10,
  status VARCHAR(20) NOT NULL DEFAULT 'Active',
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_classes_trainer (trainer_id)
);

-- 2) Schedules per class
CREATE TABLE IF NOT EXISTS class_schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class_id INT NOT NULL,
  schedule_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Active',
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_class_schedule_class (class_id)
);

-- 3) Enrollment per schedule
CREATE TABLE IF NOT EXISTS class_members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class_schedule_id INT NOT NULL,
  member_id INT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Enrolled',
  created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_class_schedule_member (class_schedule_id, member_id),
  INDEX idx_class_members_schedule (class_schedule_id),
  INDEX idx_class_members_member (member_id),
  INDEX idx_class_members_status (status)
);

