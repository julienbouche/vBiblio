function distance_levenshtein(a, b) {
        var n = a.length, m = b.length, matrice = [];
        for(var i=-1; i < n; i++) {
                matrice[i]=[];
                matrice[i][-1]=i+1;
        }
        for(var j=-1; j < m; j++) {
                matrice[-1][j]=j+1;
        }
        for(var i=0; i < n; i++) {
                for(var j=0; j < m; j++) {
                        var cout = (a.charAt(i) == b.charAt(j))? 0 : 1;
                        matrice[i][j] = minimum(1+matrice[i][j-1], 1+matrice[i-1][j], cout+matrice[i-1][j-1]);
                }
        }
        return matrice[n-1][m-1];
}

function minimum(a, b, c) {
        if (a<b && a<c) {
                return a;
        }else{
                if (b<c) {
                        return b;
                }
                else return c;
        }
}