#encoding=utf-8
import jieba
import jieba.analyse
import jieba.posseg as pseg

jieba.set_dictionary('dict/dict.txt.big')
jieba.load_userdict("dict/userdict.txt")
jieba.analyse.set_stop_words("dict/utf8stopwords_addtrad.txt")

#column: date, tweets,rtID, cntRT
data = "rtover10tweets.csv"   #open,read
dateTXT = {}
date = ""

for row in data:
	content = ""
	if(date!=row[0]):
		date = row[0]
	else:
		content += row[1]

	#準備要換日期了
	result = pseg.cut(content)
	dateTXT.update({date:result})
	
dstr = ""
for k, v in dateTXT.items():
	dstr += k + "," + str(v) + "\n"

fns = open("pseg_"+data, 'wb')
fns.write(dstr.encode('utf8'))
fns.close()	