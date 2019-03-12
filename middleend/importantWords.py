#a string s that contains only lower case letters and white space (no punctuation marks or other special characters).
#an int threshold that is the minimum length of a word that is considered important
#string s is all lowerCase Letters
uniqueWords = {}
def importantWords(s, threshold):
	words=s.split()
	for word in words:
		if len(s) > threshold:
			if word in uniqueWords.keys():
				uniqueWords[word] += 1
			else:
				uniqueWords[word] = 1 
	return uniqueWords
		
strng= "Hello my neighbor hello my sunshine hello my Clementine"
print(importantWords(strng, 6))
