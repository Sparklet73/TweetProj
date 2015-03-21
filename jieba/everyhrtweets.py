#encoding=utf-8
import csv
#import codecs
import jieba
import jieba.analyse
import jieba.posseg as pseg
import operator

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
    nr = {}
    ns = {}
    nt = {}
    dstr = ""
    #nr人名 #ns地名 #nt機構團體名
    with open(data, 'r') as f:
        reader = csv.reader(f)
        date = ""
        content = ""
        for row in reader:
            if(date != row[0]):
                words = pseg.cut(content)
                for w in words:
                    if(w.flag == "nr"):
						if(w.word not in nr):
							nr[w.word] = 1
						else:
							nr[word] += 1
                    if(w.flag == "ns"):
						if(w.word not in ns):
							ns[w.word] = 1
						else:
							ns[w.word] += 1
                    if(w.flag == "nt"):
						if(w.word not in nt):
							nt[w.word] = 1
						else:
							nt[w.word] += 1
				sorted_nr = sorted(nr.items(), key=operator.itemgetter(1), reverse=True)
				sorted_ns = sorted(ns.items(), key=operator.itemgetter(1), reverse=True)
				sorted_nt = sorted(nt.items(), key=operator.itemgetter(1), reverse=True)
                dstr = date +","
                for r in sorted_nr:
                    dstr += r + "/"
                dstr += ","
                for s in sorted_ns:
                    dstr += s +"/"
                dstr += ","
                for t in sorted_nt:
                    dstr += t +"/"
                dstr += ","
                dstr += "\n"
                fin.write(dstr.encode('utf8'))
                date = row[0]
                content = ""
                nr = []
                ns = []
                nt = []
            content += row[1]

    #fin.write(dstr.encode('utf8'))
    f.close()
    fin.close()

if __name__ == "__main__":
    data = "rawdata/HK928_RT10count.csv"
    #func_tags(data)
    func_syntac(data)
