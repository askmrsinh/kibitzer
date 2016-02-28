REGISTER elephant-bird-elephant-bird-4.13/hadoop-compat/target/elephant-bird-hadoop-compat-4.13.jar;
REGISTER elephant-bird-elephant-bird-4.13/core/target/elephant-bird-core-4.13-thrift9.jar;
REGISTER elephant-bird-elephant-bird-4.13/pig/target/elephant-bird-pig-4.13.jar;


-- Amazon_Instant_Video
meta_Amazon_Instant_Video = LOAD '../data/Amazon/meta_Amazon_Instant_Video.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
meta_Amazon_Instant_Video_tabs = FOREACH meta_Amazon_Instant_Video GENERATE (chararray)$0#'asin' AS asin, CONCAT((chararray)$0#'asin', CONCAT('-', (((chararray)$0#'title' IS NULL) ? 'null' : (chararray)$0#'title')));
A = ORDER meta_Amazon_Instant_Video_tabs BY asin;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Amazon_Instant_Video/meta.tsv';

reviews_Amazon_Instant_Video = LOAD '../data/Amazon/reviews_Amazon_Instant_Video.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
reviews_Amazon_Instant_Video_tabs = FOREACH reviews_Amazon_Instant_Video GENERATE (chararray)$0#'reviewerID' AS reviewerID, (chararray)$0#'asin', (int)(float)$0#'overall', (int)$0#'unixReviewTime';
A = ORDER reviews_Amazon_Instant_Video_tabs BY reviewerID;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Amazon_Instant_Video/reviews.tsv';


-- Books
meta_Books = LOAD '../data/Amazon/meta_Books.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
meta_Books_tabs = FOREACH meta_Books GENERATE (chararray)$0#'asin' AS asin, CONCAT((chararray)$0#'asin', CONCAT('-', (((chararray)$0#'title' IS NULL) ? 'null' : (chararray)$0#'title')));
A = ORDER meta_Books_tabs BY asin;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Books/meta.tsv';

reviews_Books = LOAD '../data/Amazon/reviews_Books.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
reviews_Books_tabs = FOREACH reviews_Books GENERATE (chararray)$0#'reviewerID' AS reviewerID, (chararray)$0#'asin', (int)(float)$0#'overall', (int)$0#'unixReviewTime';
A = ORDER reviews_Books_tabs BY reviewerID;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Books/reviews.tsv';


-- CDs_and_Vinyl
meta_CDs_and_Vinyl = LOAD '../data/Amazon/meta_CDs_and_Vinyl.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
meta_CDs_and_Vinyl_tabs = FOREACH meta_CDs_and_Vinyl GENERATE (chararray)$0#'asin' AS asin, CONCAT((chararray)$0#'asin', CONCAT('-', (((chararray)$0#'title' IS NULL) ? 'null' : (chararray)$0#'title')));
A = ORDER meta_CDs_and_Vinyl_tabs BY asin;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/CDs_and_Vinyl/meta.tsv';

reviews_CDs_and_Vinyl = LOAD '../data/Amazon/reviews_CDs_and_Vinyl.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
reviews_CDs_and_Vinyl_tabs = FOREACH reviews_CDs_and_Vinyl GENERATE (chararray)$0#'reviewerID' AS reviewerID, (chararray)$0#'asin', (int)(float)$0#'overall', (int)$0#'unixReviewTime';
A = ORDER reviews_CDs_and_Vinyl_tabs BY reviewerID;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/CDs_and_Vinyl/reviews.tsv';


-- Digital_Music
meta_Digital_Music = LOAD '../data/Amazon/meta_Digital_Music.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
meta_Digital_Music_tabs = FOREACH meta_Digital_Music GENERATE (chararray)$0#'asin' AS asin, CONCAT((chararray)$0#'asin', CONCAT('-', (((chararray)$0#'title' IS NULL) ? 'null' : (chararray)$0#'title')));
A = ORDER meta_Digital_Music_tabs BY asin;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Digital_Music/meta.tsv';

reviews_Digital_Music = LOAD '../data/Amazon/reviews_Digital_Music.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
reviews_Digital_Music_tabs = FOREACH reviews_Digital_Music GENERATE (chararray)$0#'reviewerID' AS reviewerID, (chararray)$0#'asin', (int)(float)$0#'overall', (int)$0#'unixReviewTime';
A = ORDER reviews_Digital_Music_tabs BY reviewerID;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Digital_Music/reviews.tsv';


-- Movies_and_TV
meta_Movies_and_TV = LOAD '../data/Amazon/meta_Movies_and_TV.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
meta_Movies_and_TV_tabs = FOREACH meta_Movies_and_TV GENERATE (chararray)$0#'asin' AS asin, CONCAT((chararray)$0#'asin', CONCAT('-', (((chararray)$0#'title' IS NULL) ? 'null' : (chararray)$0#'title')));
A = ORDER meta_Movies_and_TV_tabs BY asin;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Movies_and_TV/meta.tsv';

reviews_Movies_and_TV = LOAD '../data/Amazon/reviews_Movies_and_TV.json' USING com.twitter.elephantbird.pig.load.JsonLoader();
reviews_Movies_and_TV_tabs = FOREACH reviews_Movies_and_TV GENERATE (chararray)$0#'reviewerID' AS reviewerID, (chararray)$0#'asin', (int)(float)$0#'overall', (int)$0#'unixReviewTime';
A = ORDER reviews_Movies_and_TV_tabs BY reviewerID;
DESCRIBE A;
STORE A INTO 'hdfs://localhost:9000/Movies_and_TV/reviews.tsv';
