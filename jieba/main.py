#encoding=utf-8
import jieba
import jieba.analyse
import jieba.posseg as pseg

jieba.set_dictionary('dict/dict.txt.big')
jieba.load_userdict("dict/userdict.txt")
jieba.analyse.set_stop_words("dict/utf8stopwords_addtrad.txt")

dir = "rawdata/"
arr = ["f1txt.txt", "f2txt.txt", "f3txt.txt"]

for i in arr:
    d = {}
    content = open(dir + i, 'rb').read().decode("utf-8")
    #words = jieba.cut(content)
    words = pseg.cut(content)
    result = ""
    for w in words:
        #result += "\n".join("word: %s, flag: %s" % (w.word, w.flag))
        result += "[" + w.word + "/" + w.flag + "]"
        if(w.flag == "nr"):
            if(w.word not in d):
                d.update({w.word: 1})
            else:
                d[w.word] += 1

    #tags = jieba.analyse.extract_tags(content, topK=0)
    #result = "/".join(tags)
    dstr = ""
    for k, v in d.items():
        dstr += k + "," + str(v) + "\n"

    #f = open("pseg_" + i, 'wb')
    #f.write(result.encode('utf8'))
    #f.close()

    fns = open(i + "_nr", 'wb')
    fns.write(dstr.encode('utf8'))
    fns.close()
