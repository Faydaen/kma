CREATE TABLE IF NOT EXISTS db.parse_results
(
   ContentLength UInt64,
   Url           String,
   ParseDate     DateTime DEFAULT now()
) ENGINE = MergeTree() ORDER BY ParseDate;
