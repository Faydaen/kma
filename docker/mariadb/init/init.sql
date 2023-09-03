CREATE TABLE parse_results
(
    id             int          NOT NULL AUTO_INCREMENT,
    url            VARCHAR(256) NOT NULL,
    content_length INT          NOT NULL,
    parsed_at INT          NOT NULL,
    PRIMARY KEY (id)
);