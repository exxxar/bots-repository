int[] a= [1,23,0,4,6,0, 7,8,9, 0,78,0,45];
int count = 4;
int[] b = new int[a.Size - count];

int j = 0;
for (int i=0;i<a.Size;i++){
    if (a[i]!=0)
    {
    b[j] = a[i];
    j++;
    }
}

