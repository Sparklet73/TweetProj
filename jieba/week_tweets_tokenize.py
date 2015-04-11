#encoding=utf-8
import csv
import jieba
import jieba.analyse
import jieba.posseg as pseg

jieba.set_dictionary('dict/dict.txt.big')
jieba.load_userdict('dict/userdict.txt')
jieba.analyse.set_stop_words('dict/utf8stopwords_addtrad.txt')

def OutputGephi(data,bins):
    fout = open("weekGephi.csv",'wb')
    with open(data, 'r') as f:
        reader = csv.reader(f)
        
        
    fout.close()


if __name__ == "__main__":
    data = "rawdata/HKALL_week_RT10count.csv"
    bins = "HKALL_"
    