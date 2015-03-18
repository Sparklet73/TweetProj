#encoding=utf-8
import jieba.posseg as pseg
words = pseg.cut("警方稱催淚溶劑效果與胡椒噴霧相若")
for w in words:
    print w.word, w.flag
