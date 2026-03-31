-- Member module upgrades (Evosapiens Movement)
-- Run this against your `gym` database.

-- Store ID proof upload
ALTER TABLE tbl_members
  ADD COLUMN id_proof VARCHAR(255) NULL;

