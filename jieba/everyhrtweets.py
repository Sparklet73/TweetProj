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
stopwords = ["http","co","RT"]

def func_tags(data,bins):
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

    fns = open(bins+"tags_RT10count.csv", 'wb')
    fns.write(dstr.encode('utf8'))
    fns.close()
    f.close()

def func_syntac(data,bins):
    fin = open(bins+"syntac_RT10count.csv", 'wb')
    lst=["nr","ns","nt","a","eng"]
    fin.write("date,nr,ns,nt,a,eng")
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
                    if w.word in stopwords:
                        continue
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

def keyword_change():
    fres = open("HKALL_keywordChange.csv",'wb')
    fres.write("date,nr,ns,nt,a,eng")
    lst = ["nr","ns","nt","a","eng"]
    dlst = {"nr":1,"ns":2,"nt":3,"a":4,"eng":5}
    dkw = defaultdict() #過濾出的關鍵字
    for grammar in lst:
        dkw[grammar] = []
    dstr = ""
    with open("HKALL_syntac_RT10count.csv", 'r') as f:
        reader = csv.reader(f)
        datehr = "date"
        for rowx in reader:
            row = [x.decode('utf8') for x in rowx]
            tempkw = defaultdict()
            for dif in lst:
                tempkw[dif] = row[dlst[dif]].split('/')
                sdkw = set(dkw[dif])
                stmp = set(tempkw[dif])
                dkw[dif] = list(stmp.difference(sdkw))
            datehr = row[0]
            dstr = datehr + ","
            for elem in lst:
                for w in dkw[elem]:
                    dstr += w + "/"
                dstr += ","
            dstr += "\n"
            fres.write(dstr.encode('utf8'))

    f.close()
    fres.close()


if __name__ == "__main__":
    data = "rawdata/HKALL_tweets_RT10count.csv"
    bins = "HKALL_"
    #func_tags(data,bins)
    #func_syntac(data,bins)
    keyword_change()
