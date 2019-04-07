def importantWords(s, threshold):
    uniqueWords = {}
    words=s.split()
    for word in words:
        if len(s) >= threshold:
            if word in uniqueWords.keys():
                uniqueWords[word]+=1
            else:
                uniqueWords[word]=1 
    print(uniqueWords)