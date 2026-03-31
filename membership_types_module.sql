-- Membership Types module migration for Evosapiens Movement
-- Run this against your `gym` database.

CREATE TABLE IF NOT EXISTS membership_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Member → Membership Type relationship
ALTER TABLE tbl_members
  ADD COLUMN membership_type_id INT NULL;

-- Optional index for faster joins/filters
CREATE INDEX idx_members_membership_type_id ON tbl_members (membership_type_id);

