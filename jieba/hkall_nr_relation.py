#encoding=utf-8
import csv
import jieba
import jieba.analyse
import jieba.posseg as pseg
from collections import defaultdict
import operator
import codecs

jieba.set_dictionary('dict/dict.txt.big')
jieba.analyse.set_stop_words('dict/utf8stopwords_addtrad.txt')
keywords = ["http","co","RT","https","香港","雨傘革命","占中","佔中","雨傘","清場",
                          "佔領","中環","運動","HKStudentStrike","雨遮","革命","遮打","普選",
                          "hongkong","umbrellarevolution","occupycentral","occupyhk","hkclassboycott"]

#to build the nr&nt relationship
def rt10text():
    jieba.load_userdict('dict/userdict.txt')
    rawfile = "rawdata/HKALL_rt10_rawtweets.csv"
    output = open("HKALL_rt10tklist.csv", 'wb')
    gdf = open("rt10tklist.gdf",'wb')
    gstr = "nodedef>name VARCHAR,label VARCHAR,no_times INT\n"
    nodeid = 1
    nodelst = defaultdict(int)
    edgelst = []

    d_n_times = defaultdict(int)
    d_relation = defaultdict(dict)
    n = ["nr","nt"]
    pstr = "n,times,relation\n"
    with open(rawfile, 'rb') as f:
        reader = csv.reader(f)
        for row in reader:
            templist = []
            words = pseg.cut(row[0])
            for w in words:
                if w.flag == "nr" or w.flag == "nt":
                    #ww = w.word
                    #tmpstr = ww.decode('utf8')
                    if len(w.word) > 1: #一個字的不要算
                        templist.append(w.word)
            for k in templist:
                d_n_times[k] += 1
                for v in templist:
                    if k != v:
                        if v not in d_relation[k]:
                            d_relation[k].setdefault(v, 0)
                        cnt = d_relation[k][v] + 1
                        d_relation[k].update({v:cnt})
        sorted_elem = sorted(d_n_times.items(),key=operator.itemgetter(1),reverse=True)
        for kw in sorted_elem:
            pstr += kw[0] + "," + str(kw[1]) + ","
            gstr += str(nodeid) + "," + kw[0] + "," + str(kw[1]) +"\n"
            nodelst[kw[0]] = nodeid
            nodeid +=1
            relation_sorted = sorted(d_relation[kw[0]].items(), key=operator.itemgetter(1),reverse=True)
            for kwkw in relation_sorted:
                pstr += kwkw[0] + "," + str(kwkw[1]) + ","
                if kwkw[0] not in nodelst:
                    nodelst[kwkw[0]] = nodeid
                    nodeid += 1
                edgelst.append([nodelst[kw[0]],nodelst[kwkw[0]],kwkw[1]])
            pstr += "\n"

        gstr += "edgedef>node1 VARCHAR,node2 VARCHAR,weight DOUBLE\n"
        for e in edgelst:
            gstr += str(e[0]) +","+ str(e[1])+","+str(e[2])+"\n"

        output.write(pstr.encode('utf8'))
        gdf.write(gstr.encode('utf8'))

    f.close()
    output.close()
    gdf.close()

if __name__ == "__main__":
    rt10text()

