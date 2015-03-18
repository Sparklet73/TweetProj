#encoding=utf-8
import jieba
import jieba.analyse

jieba.set_dictionary('dict/dict.txt.big')
#jieba.analyse.set_stop_words('utf8stopword.txt')
#stopwords = {}.fromkeys([line.rstrip() for line in open('utf8stopwords.txt')])
#stopwords = {}.fromkeys(['吗'])
jieba.load_userdict("dict/userdict.txt")

content = open('RT.txt', 'rb').read()
final=''
words = jieba.cut(content) #默認是精確模式
#print content
#print
for word in words:
    print word+'/',
print
words = jieba.cut(content, HMM=True)
for word in words:
    print word+'/',
