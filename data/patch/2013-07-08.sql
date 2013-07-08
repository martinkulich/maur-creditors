BEGIN;
DROP FUNCTION creditor_interest(integer, integer, integer);
DROP FUNCTION creditor_paid(integer, integer, integer);
DROP FUNCTION creditor_capitalized(integer, integer, integer);
COMMIT;