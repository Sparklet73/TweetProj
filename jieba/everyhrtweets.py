#encoding=utf-8
import csv
#import codecs
import jieba
import jieba.analyse
import jieba.posseg as pseg
import operator
from collections import defaultdict

jieba.set_dictionary('dict/dict.txt.big')
jieba.load_userdict("dict/userdict.txt")
jieba.analyse.set_stop_words("dict/utf8stopwords_addtrad.txt")
stopwords = ["http","co","RT","hkclassboycott","HongKong","OccupyCentral"]

def func_tags(data):
    dateTXT = {}
    with open(data, 'r') as f:
        reader = csv.reader(f)
        date = ""
        content = ""
        for row in reader:
            if(date != row[0]):
                tags = jieba.analyse.extract_tags(content, topK=20)
                for sw in stopwords:
                    if sw in tags:
                        tags.remove(sw)
                result = "/".join(tags)
                dateTXT.update({date:result})
                date = row[0]
                content = ""
            content += row[1]

    dstr = ""
    for k, v in dateTXT.items():
        dstr += k + "," + v + "\n"

    fns = open("tags_RT10count.csv", 'wb')
    fns.write(dstr.encode('utf8'))
    fns.close()
    f.close()

def func_syntac(data):
    fin = open("syntac_RT10count.csv", 'wb')
    lst=["nr","ns","nt","v","a","r","z"]
    fin.write("date,nr,ns,nt,v,a,r,z")
    dSyn = defaultdict(dict)
    for grammar in lst:
        dSyn[grammar]
    dstr = ""
    with open(data, 'r') as f:
        reader = csv.reader(f)
        date = ""
        content = ""
        for row in reader:
            if(date != row[0]):
                words = pseg.cut(content)
                for w in words:
                    if(w.flag in dSyn):
                        if(w.word not in dSyn[w.flag]):
                            dSyn[w.flag][w.word] = 0
                        else:
                            dSyn[w.flag].update({w.word:dSyn[w.flag][w.word]+1})
                dstr = date + ","
                for elem in lst:
                    sorted_elem = sorted(dSyn[elem].items(), key=operator.itemgetter(1), reverse=True)
                    for kw in sorted_elem:
                        dstr += kw[0] + "/"
                    dstr += ","
                dstr += "\n"
                fin.write(dstr.encode('utf8'))
                date = row[0]
                content = ""
                dSyn = defaultdict(dict)
                for grammar in lst:
                    dSyn[grammar]
            content += row[1]

    f.close()
    fin.close()

if __name__ == "__main__":
    data = "rawdata/HK928_RT10count.csv"
    #func_tags(data)
    func_syntac(data)
