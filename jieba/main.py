#encoding=utf-8
import csv,sys
import jieba
import jieba.analyse
import jieba.posseg as pseg
import operator
from collections import defaultdict
from _gexf import Gexf
import time
import chardet
import codecs
import re

jieba.set_dictionary('dict/dict.txt.big')
#add my own dict by hashtag_zh
#jieba.load_userdict("dict/userdict_byhashtag_zh.txt")
jieba.analyse.set_stop_words("dict/utf8stopwords_addtrad.txt")
stopwords = ["http","co","RT","https","香港","雨傘革命","占中","佔中","雨傘","清場",
             "佔領","中環","運動","HKStudentStrike","雨遮","革命","遮打","普選",
             "hongkong","umbrellarevolution","occupycentral","occupyhk","hkclassboycott"]

default_encoding = 'utf-8'
if sys.getdefaultencoding() != default_encoding:
    reload(sys)
    sys.setdefaultencoding(default_encoding)

#preprocess tweets tokenization
#just cut tweets without remove stopwords
def tweetsPreprocessing():
    jieba.load_userdict("dict/userdict_byhashtag.txt")
    rawfile = "rawdata/HKALL_tweets_zh.csv"
    output = open("HKALL_tweets_jieba_nonoun.csv", 'wb')

    with open(rawfile, 'rb') as f:
        reader = csv.reader(f)
        for row in reader:
            seg_list = jieba.cut(row[20])
            result = "/".join(seg_list)
            str = row[0]+","+row[1]+","+result+"\n"
            output.write(str.encode('utf8'))

    f.close()
    output.close()

def func_tags_hr(data,bins):
    dateTXT = {}
    with open(data, 'r') as f:
        reader = csv.reader(f)
        date = ""
        content = ""
        for row in reader:
            if(date != row[0]):
                tags = jieba.analyse.extract_tags(content, 30)
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

    fns = open(bins+"tags_RT10cnt.csv", 'wb')
    fns.write(dstr.encode('utf8'))
    fns.close()
    f.close()

def tags_week(bins):
    weekTXT = {}
    with open("rawdata/HKALL_week_RT10count.csv", 'r') as f:
        reader = csv.reader(f)
        week = ""
        content = ""
        for row in reader:
            if(week != row[1]):
                tags = jieba.analyse.extract_tags(content, 50)
                for sw in stopwords:
                    for n in tags:
                        n = n.encode('utf8')
                        m = re.search(sw,n,re.IGNORECASE)
                        if bool(m) is True:
                            tags.remove(n)
                result = "/".join(tags)
                weekTXT.update({week:result})
                week = row[1]
                content = ""
            content += row[2]
    dstr = ""
    for k, v in weekTXT.iteritems():
        dstr += k + "," + v + "\n"
    fns = open(bins+"week_tags_ovrRT10.csv", 'wb')
    fns.write(dstr.encode('utf8'))
    fns.close()
    f.close()


def func_syntac(data,bins):
    jieba.load_userdict("dict/userdict.txt")
    fin = open(bins+"syntac_RT10cnt_nrnt.csv", 'wb')
    lst=["nr","nt"]
    fin.write("date,nr,nt")
    dSyn = defaultdict(dict)
    for grammar in lst:
        dSyn[grammar]
    dstr = ""
    with open(data, 'r') as f:
        reader = csv.reader(f)
        date = ""
        content = ""
        #tweetscnt = 0
        for row in reader:
            if(date != row[0]):
                words = pseg.cut(content)
                for w in words:
                    if w.word in stopwords:
                        continue
                    uniword = w.word.decode('utf8')
                    if len(uniword) == 1:
                        continue
                    if w.flag in dSyn:
                        if(w.word not in dSyn[w.flag]):
                            dSyn[w.flag][w.word] = 1
                        else:
                            dSyn[w.flag].update({w.word:dSyn[w.flag][w.word]+1})
                dstr = date + ","
                for elem in lst:
                    sorted_elem = sorted(dSyn[elem].items(), key=operator.itemgetter(1), reverse=True)
                    for kw in sorted_elem:
                        dstr += kw[0] + "/"
                    dstr += ","
                #dstr += "\n" + date + ","
                #for elem in lst:
                #    sorted_elem = sorted(dSyn[elem].items(), key=operator.itemgetter(1), reverse=True)
                #    for kw in sorted_elem:
                #        per = "{0:0.0f}%".format(float(kw[1])/tweetscnt * 100)
                #        dstr += per + "\t"
                #    dstr += ","
                dstr += "\n"
                fin.write(dstr.encode('utf8'))
                date = row[0]
                content = ""
                #tweetscnt = 0
                dSyn = defaultdict(dict)
                for grammar in lst:
                    dSyn[grammar]
            #tweetscnt += 1
            content += row[1]

    f.close()
    fin.close()

#to help look what word is emerging.
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

#etree can't take good work in chinese.
def makeGexf():
    raw_file_name = "gexf_format_test.csv"
    #raw_file_name = "HKALL_tags_RT10count.csv"
    gexf = Gexf("Ching-Ya Lin", "Keyword relation by hours.")
    graph = gexf.addGraph("undirected","dynamic","graph!","datetime")
    wid = graph.addNodeAttribute("Weight","1","integer","dynamic")
    ewid = graph.addEdgeAttribute("EdgeWeight","1","integer","dynamic")

    if(1):
        f = codecs.open( raw_file_name, "r", "utf-8" )
    #with open(raw_file_name, 'r') as f:
        reader = csv.reader(f)
        w_d = defaultdict() #weight_dictionary for all keywords
        e_w_d = defaultdict() #edge
        for row in reader:
            #row = [x.decode('utf8') for x in rowx]
            tt = time.strptime(row[0], "%Y-%m-%d %H")
            rtt = time.strftime("%Y-%m-%d %H:00:00", tt)
            words = row[1].split('/')
            templist = [] #one row list to help addEdge
            for w in words:
                # useless : w = w.encode('utf8')
                #print w
                #print chardet.detect(w)
                templist.append(w)
                if graph.nodeExists(w):
                    n = graph.nodes[w]
                    v = w_d[w] + 1
                    #v = int(n.attributes[int(wid)]["value"])
                    #n.attributes will get the initial value which we don't want.
                    n.addAttribute(wid,str(v),rtt)
                    w_d[w] = v
                else:
                    new = graph.addNode(w,w,rtt)
                    new.addAttribute(wid,"1",rtt)
                    w_d[w] = 1

            for w1 in templist:
                for w2 in templist:
                    if w1 != w2:
                        s1 = w1 + w2
                        s2 = w2 + w1
                        if graph.edgeExists(s1):
                            e = graph.edges[s1]
                            val = e_w_d[s1] + 1
                            e.addAttribute(ewid,str(val),rtt)
                            e_w_d[s1] = val
                        elif graph.edgeExists(s2):
                            e = graph.edges[s2]
                            val = e_w_d[s2] + 1
                            e.addAttribute(ewid,str(val),rtt)
                            e_w_d[s2] = val
                        else:
                            enew = graph.addEdge(s1,w1,w2,1,rtt)
                            enew.addAttribute(ewid,"1",rtt)
                            e_w_d[s1] = 1
                templist.remove(w1)

    output_file = open("eventhr.gexf","w")
    gexf.write(output_file)

def print_ve_gexf():
    rawfile = "HKALL_927to1004syntac_RT10cnt_nr.csv"
    nodedict = defaultdict(list)
    edgedict = defaultdict(list)
    edge_sourcetarget = defaultdict(list)

    with open(rawfile, 'r') as f:
        reader = csv.reader(f)
        for row in reader:
            tt = time.strptime(row[0], "%Y-%m-%d %H")
            rtt = time.strftime("%Y-%m-%d %H:00:00", tt)
            #rtt = row[0]
            words = row[1].split('/')
            templist = [] #to help build edge
            for w in words:
                templist.append(w)
                nodedict[w].append(rtt)
            for w1 in templist:
                for w2 in templist:
                    if w1 != w2:
                        s1 = w1 + w2
                        s2 = w2 + w1
                        if s1 in edgedict.iterkeys():
                            edgedict[s1].append(rtt)
                        elif s2 in edgedict.iterkeys():
                            edgedict[s2].append(rtt)
                        else :
                            edgedict[s1].append(rtt) #第一次出現的邊必須先確定好id
                            edge_sourcetarget[s1].append(w1) #source
                            edge_sourcetarget[s1].append(w2) #target
                templist.remove(w1)

    #gexf file
    gf = open("nr_927_1004.gexf", 'wb')
    gf.write("<nodes>\n")
    for key, value in nodedict.iteritems():
        for first in value:
            if first == value[0]:
                gf.write("\t<node id=\"" + key + "\" label=\"" + key + "\" start=\"" + first + "\">\n")
        gf.write("\t\t<attvalues>\n")
        for v in value:
            i = value.index(v) + 1
            gf.write("\t\t\t<attvalue for=\"0\" value=\"" + str(i) + "\" start=\"" + v + "\"/>\n")
        gf.write("\t\t</attvalues>\n")
        gf.write("\t</node>\n")
    gf.write("</nodes>\n")
    gf.write("<edges>\n")
    for key, value in edgedict.iteritems():
        for first in value:
            if first == value[0]:
                gf.write("\t<edge id=\"" + key + "\" source=\"" + edge_sourcetarget[key][0] +
                        "\" target=\"" + edge_sourcetarget[key][1] + "\" start=\"" + first + "\" weight=\"1\">\n")
        gf.write("\t\t<attvalues>\n")
        for v in value:
            i = value.index(v) + 1
            gf.write("\t\t\t<attvalue for=\"0\" value=\"" + str(i) + "\" start=\"" + v + "\"/>\n")
        gf.write("\t\t</attvalues>\n")
        gf.write("\t</edge>\n")
    gf.write("</edges>\n")
    gf.write("</graph>\n")
    gf.write("</gexf>\n")
    gf.close()

if __name__ == "__main__":
    data = "rawdata/HKALL_tweets_RT10count.csv"
    bins = "HKALL_"
    #func_tags_hr(data,bins)
    #tags_week(bins)
    func_syntac(data,bins)
    #keyword_change()
    #makeGexf()
    #print_ve_gexf()
    #tweetsPreprocessing()
