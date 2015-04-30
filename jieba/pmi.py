import nltk
from nltk.corpus import brown
from nltk import WordNetLemmatizer
from math import log

wnl=WordNetLemmatizer()
nltk.download()
_Fdist = nltk.FreqDist([wnl.lemmatize(w.lower()) for w in brown.words(categories='news')])

_Sents = [[wnl.lemmatize(j.lower()) for j in i] for i in brown.sents(categories='news')]

def p(x):
    return _Fdist[x]/float(len(_Fdist))

def pxy(x,y):
    return (len(filter(lambda s :  x in s and y in s ,_Sents))+1)/ float(len(_Sents) )

def pmi(x,y):
    return  log(pxy(x,y)/(p(x)*p(y)),2)
