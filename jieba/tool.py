#encoding=utf-8
from sets import Set
import jieba
import jieba.analyse
import jieba.posseg as pseg
import csv

jieba.set_dictionary('dict/dict.txt.big')
jieba.load_userdict('dict/userdict.txt')
jieba.analyse.set_stop_words('dict/utf8stopwords_addtrad.txt')

file = open('HKALL_week_tags_ovrRT10.csv','rb')
userlist = open('userlist_week_high_weight.txt','wb')
userSet = Set()
reader = csv.reader(file)
for r in reader:
    words = pseg.cut(r[1])
    for w in words:
        if w.flag == "eng":
            userSet.add(w.word)

for usr in userSet:
    userlist.write(usr+'\n')

file.close()
userlist.close()
