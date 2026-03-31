-- Payment pending/partial system (Evosapiens Movement)
-- Run this against your `gym` database.

-- Member-level summary fields
ALTER TABLE tbl_members
  ADD COLUMN total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  ADD COLUMN paid_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  ADD COLUMN pending_amount DECIMAL(10,2) NOT NULL DEFAULT 0;

-- Payment history fields (tbl_revenue is used as payment ledger)
ALTER TABLE tbl_revenue
  ADD COLUMN payment_status VARCHAR(20) NOT NULL DEFAULT 'Paid',
  ADD COLUMN total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  ADD COLUMN pending_amount DECIMAL(10,2) NOT NULL DEFAULT 0;

