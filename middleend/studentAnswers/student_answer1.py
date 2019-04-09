vowels=['a','e','i','o','u']
def vowelCount(txt):
	count=0
	for vowel in vowels:
		for ch in txt:
			if str.lower(ch) == vowel:
				count= count + 1
	return count