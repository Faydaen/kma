CREATE TABLE parse_results
(
    CounterID     UInt32,
    ContentLength UInt64,
    Url String,
    ParseDate     DateTime DEFAULT now()
) ENGINE = MergeTree() ORDER BY ParseDate;
