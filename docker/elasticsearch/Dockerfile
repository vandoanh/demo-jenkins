FROM docker.elastic.co/elasticsearch/elasticsearch:6.6.0
# RUN yum install maven\
#     && yum install git\
#     && git clone https://github.com/duydo/vn-nlp-libraries.git\
#     && cd vn-nlp-libraries/nlp-parent\
#     && mvn install\
#     && git clone https://github.com/duydo/elasticsearch-analysis-vietnamese.git\
#     && cd elasticsearch-analysis-vietnamese\
#     && mvn package -DskipTests

# COPY elasticsearch-analysis-vietnammese.zip /usr/share/elasticsearch/

# RUN cd /usr/share/elasticsearch\
#     && bin/elasticsearch-plugin install file:///usr/share/elasticsearch/elasticsearch-analysis-vietnammese.zip\
#     && bin/elasticsearch-plugin install analysis-icu
    
EXPOSE 9200 9300
