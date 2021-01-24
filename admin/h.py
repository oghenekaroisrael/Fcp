def plusMinus(arr):
    positive = 0
    negative = 0
    zero = 0
    base = len(arr)
    for i in range(len(arr)):
        if arr[i] > 0:
            positive+=1
        elif arr[i] < 0:
            negative+=1
        else:
            zero+=1
    return "{0:.6f}".format(positive/base), "{0:.6f}".format(negative/base), "{0:.6f}".format(zero/base)

def staircase(num_sntairs):
    for i in range(1, n + 1):
        print(' ' * (n - i) + '#' * i)
if __name__ == "__main__":
    print(plusMinus([-4, 3, -9, 0, 4, 1 ]))