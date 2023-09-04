CREATE TABLE parse_results
(
    ContentLength UInt64,
    Url           String,
    ParseDate     DateTime DEFAULT now()
) ENGINE = MergeTree() ORDER BY ParseDate;
