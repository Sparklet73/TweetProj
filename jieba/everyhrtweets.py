#encoding=utf-8
import csv
#import codecs
import jieba
import jieba.analyse
import jieba.posseg as pseg

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
    dsyntac = {} #{"date",mix{}}
    nr = []
    ns = []
    nt = []
    mix = {"nr":nr,"ns":ns,"nt":nt}
    #mix[nr] = list()
    #mix[ns] = list()
    #mix[nt] = list()
    #nr人名 #ns地名 #nt機構團體名
    with open(data, 'r') as f:
        reader = csv.reader(f)
        date = ""
        content = ""
        for row in reader:
            if(date != row[0]):
                words = pseg.cut(content)
                for w in words:
                    if(w.flag == "nr" and w.word not in mix["nr"]):
                        mix["nr"].append(w.word)
                    if(w.flag == "ns" and w.word not in mix["ns"]):
                        mix["ns"].append(w.word)
                    if(w.flag == "nt" and w.word not in mix["nt"]):
                        mix["nt"].append(w.word)
                dsyntac.update({row[0]:mix})
                data = row[0]
                content = ""
            content += row[1]

    dstr = ""
    for k, v in dsyntac.items():
        dstr += k + ","
        for v, j in mix.items():
            dstr += v + ":"
            for i in j:
                dstr += i + "/"
        dstr += "\n"

    fin = open("syntac_RT10count.csv", 'wb')
    fin.write("date,nr,ns,nt\n")
    fin.write(dstr.encode('utf8'))
    f.close()
    fin.close()


if __name__ == "__main__":
    data = "rawdata/HK928_RT10count.csv"
    #func_tags(data)
    func_syntac(data)
