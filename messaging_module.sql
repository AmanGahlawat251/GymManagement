-- Messaging module migration for Evosapiens Movement
-- Run this against your `gym` database.

-- Birthday automation field
ALTER TABLE tbl_members
  ADD COLUMN dob DATE NULL;

-- Message logs (WhatsApp + Email)
CREATE TABLE IF NOT EXISTS message_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  type VARCHAR(20) NOT NULL,            -- 'whatsapp' or 'email'
  message_type VARCHAR(50) NOT NULL,    -- 'welcome','expiry','birthday','offer','custom'
  status VARCHAR(20) NOT NULL,          -- 'sent','failed'
  message TEXT NULL,
  provider_response TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_message_logs_member (member_id),
  INDEX idx_message_logs_type (type),
  INDEX idx_message_logs_msg_type (message_type)
);

