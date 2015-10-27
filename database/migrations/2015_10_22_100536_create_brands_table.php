<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'brands',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('company_name');
                $table->string('introduction_text');
                $table->binary('logo');
                $table->timestamps();
            }
        );

        $this->addDefaultBrand();
    }

    /**
     * Add the default branding
     */
    public function addDefaultBrand()
    {
        // Always recreate the default branding for the system
        DB::table('brands')->where('id', '=', '1')->delete();

        $abuseio_base64 =
            'iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAIAAAAiOjnJAAAxkUlEQVR4nOydB3wTdf/Hk1wuu0m6Ny2dzLKXDBkCypDhZCnwsBQBAc' .
            'EHeQBBEBAH/hERBVw4AEWWgCwZyt6rtHTv3WbPS/7f5jCENLmkyV3a4r1fvPSaXH73u7vPfcdvHdtsNjNoaMiG1dAVoHk8oYVFQwm0' .
            'sGgogRYWDSXQwqKhBFpYNJRAC4uGEmhh0VACLSwaSqCFRUMJtLBoKIEWFg0l0MKioQRaWDSUQAuLhhJoYdFQAi0sGkqghUVDCbSwaC' .
            'iBFhYNJdDCoqEEWlg0lEALi4YSaGHRUAItLBpKoIVFQwm0sGgogRYWDSWwG7oCNI+AydWqk7c017KMZTUMk5kTFybo1UrYo0VD16ve' .
            '0MJqRGhuZOcMX4FVyO0+F/ZPifryDTQisEFq5RlMehmjRkLl5kOlS38wKTQOv2X58QVPtPR7uqOwdxteq2gf180DaGE1CmS/ns2f8J' .
            'GbO7NDpbz2caIB7bgtowWdEhCpkNK6eQYtrIbHpDPcbzfLkFfuyY8RFkvEQ6ODOTHB7BAp/IOwDI0IQGNDOdFBTE6DhTp0jNXwyA9c' .
            '8lBVAGYyydQ6Wa7udq79VyiChgfwOyXw2jUX9mzF75zA4qJeVtV9aGE1POozdygp14CBXuGf/Ldz8Bc7PCD03bGS0T1YQh4lh3sUuh' .
            '2r4dFnFfvgKMbiqsLpn6W3n12z84wPDkcLq+Ex64w+O5axsLJg4vr7HedormdReiBaWA0PEiT28RF19wryXv6A0kPQwmp4eO3jfH9Q' .
            'iL0qNx+irny6ucEek0qru19kyK8wltbo0gr4HeO1qfmc2FDRoA5mtU5zNbPq66PgvCCxZ6IIJlMZy2QMDHvwYwRBRDwIk/0nDuAmRb' .
            'LD/N1JxAzFVektXzPrfecQccBSJt//kqJUkRZWLSatvubHU8oTN7S38yCBB8Xos0t5bWLEI7prrmWaDUYWj6O+mF7fRgGWRCDs3cZ/' .
            'Qj/x8K7Eexa++WX1l394cQYekpS6iRMTQkXJ/3Zh6dILq785Xr39zwc9dCgCWTr+Fdos2PPmpUeBovye7uT3dEf4r8MdjFWK/Fc+Vp' .
            '24Scrh3Cf2wFJR/3ZUlPzvFZaxXFY8f6vsl799eVBB79bB80f5DepQ9yuwmlCf6q+P+bI+0dvnS0Y/QUXJ/y5h6TKKdPeL2cFi2Y4z' .
            '1d+dcNbjSzkIS9i7Na91M5ZECI7yoTMyYjW7zxa/ta3uAAeKoIXlLZpbOWCiELGg5qfTVV8ehkCqoWv0EHCU4Z9MFT/zwEsqT9+u3L' .
            'BfczXLWFxF9aFpYXkOZHkQmPM6xKvP3ytZ+HVDV8cJKBL52WtgvawfgGeU7zlfvu5XXWoBdYdttmOheHg3Kkp+nIWlOn1bcytX0C3J' .
            'JFOXffArVV1y5CEd3zfq89cZbMT2Q6xGhclUhoJKQ365bM95xe+XSDS3dPBeP8x6Y85z74OwQha9oDx6XX3uXkPXyF2C5o8Ke288wQ' .
            '4l73xXsX4vWYeLPbxc1KcNWaXZ8hi2vMMjnj18her4DYYBK1u9qwmpCqj4v326tEJn31ZtO0qiqgDq/OzjJizN9ayM7vMfer1/GqWa' .
            'DAas+L/f1P0Yk6uL3vyq6I0vyD0a4k/V6NPHZTyWEVNdSKv8/KB834VGlfF5gPKPq9q7ebxWzR5+ZMSqthyR779I+rHYYf6kl/mgZI' .
            'rK9THq61lFszdTmkD5Eu3NHFthKU7dLl3+IxXWlyWgatDf4yCsig0Hqr8/8dioCjCU1Vi3ladvF0xa3+R8epMXFsSzJW831tYpj/nH' .
            'myuOX88d/h51xzGrtRSV3LSD95qfT5cu2d7QtSAfTlwYA58TNvZDSg9krFZSVHJTtVgmnaHwtc9lP59u6IpQAr9TQvaw5T4Y7KC9lS' .
            'sZ0Z2KkpuqxSp5+5vHVVV+w7rIdv7lmyE0misZJhUl3rDptbzDhSia+1XN9pO+PChLIuDEhtaOCBVwOTEhvPbN2YFi7b0C1albZgNm' .
            'KKx0MK3PUwS9W2supPloQCnCij+9ht8hnvSCm5iwdOmFeWM+8EUCiCKBM57xG9SRlxILYnI2Fw9uv9lQqwCQlz67VLb7rLG0BqRvrJ' .
            'Crz99rEqmc9NUBUZteJ73YpiQsk1af0WWePpPCWXjclFjdzRzYCH7nRelLvbmJEfUtQZ9bVrn5MFYhD10xrmrLEcWBi1pLgY0WJEjc' .
            'Mo/8tLrJBO/GclnuS2spVRWDwzbVqPBNsFKeLbYBjjL8/VfAaKnOp0lf7BU8dwRYr7JVO2p2nPGNAWMKuLVG1O1jwTOgvZvHDpbAPx' .
            'Kr0USCdyOWP/4jzfk0Sg8ieqo9t+WDFYJYQi7Lj+9xUeA6/Qa04yZFwgZILeKTqc0PLw9bNxnSvUf2Q1ic+HABieuqIazQpS9zmoUw' .
            'OWxQmJs/Am1hcjVpdbDQNIRV/dMpFdmjqWrXMlgxjt892foJVqUQ9mxZu4Uior4pJPY5gryEPVoGzRwaf2Zt4MyhICYI3YLmj0q89i' .
            'l8ArE/KUfhJEVKnuspeqpDs1/+G/D6UPfrr00vRMnuNGwCrlB3v6hk8ffklika3DFq6xx2gAiTqa2GUPRk28DXhphUOumYPmhkIEWL' .
            'Z4Svmwz/8G3t3fyM7vNJmQsUsnys7OfTogHtODHBkENIRveodG+ADRg2fVYp6bMLG7vFgmDF4eqJ3hCybEzsb4tBVbDNEj30d7w2MZ' .
            'hSE7psDO7CSDyiQ7AaVe7oVaSoSjyqh7BXa0iWIXWAPyUv9ALBuPlbQfdkBC7Fo8NWvaexW6ySpT+QNbmPYcmAQpeOCZgyyPqJIf+R' .
            'wtFQqoaR2KHLLM4ZupysUxMNbK88fgMeGP9xfeGRAEOIVSvc/C0aHcTvQP4c/0YtLMXhK7Uzakgi4LUhYSvG2ZkiTnwYvgEeAY0JNu' .
            'uNPlgFDzLcnBHvkaIqNC7MkFUCOUHA+H5Wq4NIBWVr/3Tn55Cg+D3VwW9Ae+9rYkfjFRY4wfxJ60mJoFkSgf/4ftbIxpagmcPAfUCY' .
            'Fbt/KSIR+mZtxbJVOw1Zpd6XAwY4/s/3tan5/Laxtp+jEYHiZzrLd7meixv17Vyxk8nZXtJ4hVX55WGTzNscGITiN6yL3+CO/hP6O9' .
            'shcv202i0jRnqc4RD54Ss1P50ipaiA/wzUpRdyE8LrfiV9uQ+3ZXTVV39Ubzvq4JcoIhnZI2jeSH675qTUpC6NtOXdWKVMbznDy5nK' .
            'YKjCVr8q6pdC0boX9caIFS/+rnLDAVIKA9+ddGuj+mIa8fAE2d7zeouvNKt1kC4wzGa0WYigSyLVtrmRWqyaH096qSq47vEn1xirFI' .
            '1FVQxG8aJvKzf+TlZpoe+ORcP9QVUmrV6XVmg2GNnBkronS9GoGJc0RmEpjl/H12P1hohPpjJ5YPEbyxroJct+IFFV0nF9xcO7lq/b' .
            'XbPrL9uBFfgS8PCV5PmeiFiAf5ifn5+amsqwrN4VGBjYvj35oXpdGqMrTG8/21BQAabb4xL8/zMocsN0EqvkJdXfnyicvpGUorgpsf' .
            'yUWOlLvfPGf0QQg7LDA+JOrAIDdu7cuSlTpsjltQ2BXC4Xw7ANGzYMGzaMlMoQ0OiEZSipToub4k0J3DYxkCv5ZtFplxjLZeUf7q7Z' .
            'fhLzfhAwirBDpAln18l2ny15+xvXA7ZQRL5k6Gvff1xV9cjiIiKRaPPmzX369PG2PoQ0LmHpMos1VzIKJq73uAQIrWJ2v0PRtPH6Yi' .
            'iuyur3DlmtoNLxfcPWTKz6/GDZ+zvd/MlCceot1EFLKZ/P37lzJ6U+sXF16ajP3tOlF3n8c4gwWuZuc5h+NwjyfRfIUhUSJBYP78Zk' .
            'scrdnmJfxTQ4VBWg0WhOnDhBSsWc0biCd8XhK2ZPW0RZfvyYPf/T3MoVdk4gt1aeoTx9u2qz190GCIuBmSQv9wlfOxGSvvJP9rgfem' .
            'qYRtTMNDAde6TiYmrfWtCIhKW9my+d0A+iB89+HvTWKOqa++qFSaU1FFbmjn7fm/yDYWm8bbbrvwwWU/bLWXwUXvU3x93/eQ3L6ExV' .
            'QFGR557BHRpAWDqdDrLf0tJSSFXA2UMCXFlZ2blzZyGbwY0P19/36IRRRDy0C9k19QR9blnOsBXGCpmXqgL43ZKxSgW/Q1zUptcNBk' .
            'PWzbsZWZkxDHeHH8qYBoJv/fz8vKweMfUWVnp6ek1NDYfDMRqNQUFBsbGx7vwKdj5+/LhMJtu1a9f58+cZlvgRPL11hyChOKZFokSB' .
            'iQRF7QwSvpmVhImEZnf7WBARv/jqvYBIiVAoZLOpelrgLFQqVVpaGlyEwsJCHo8nlUpzcnLKysqaNWvWoUOHTqKI0ufWVshrjnMrrv' .
            'nJSxCd1IR2NIhHacMJzkXBNJ7nVJey9FUsfQTGQxjMQbpg2N9vcEeTRs9Njtq2bdu3336blZXFkDIEZqSPLmCsJjLYxCGurchMdB2e' .
            'fPJJh59nZmbC6RQUFKjV6ubNm8P1hNsUEhISHh4Ot8z9a1uPewDH27p1661bt86cORMQENC6dWsURUeOHNmvXz+4vnX3h4fs77//Pn' .
            'r06G0Ler3eVky2qgIqVPKKK1dqt/iMffyycIyLMcwvayIG6II4hBkG7HaCU/GL6VbeohOMRbW59LBhw2bPnh0dTeZrSOFcbt68CXd3' .
            '//79IC9nu8Fdb20SoUJmBltdhujhk2JEl4oqj3IrPpW1lpofGUxnZJiOcSuOcCsy2Co7n/Ujv3CAuPmQIN2A5we/8MIL+KOIo2Zih3' .
            'nlWYh6rbwFj0H04AWanI7dE4vFYBrsPgS/ceDAgb1794K2wJnAaYKM4IEB2wZeJSYmBu4y/HDs2LFgWSIiXEwzcbe5AfzX6tWrwd7g' .
            'TW1WuFzud99998QT9guknj59Gm4D+LsbN264U75DhCbkOU14uInbV+/0dci/8Uq+FObZfdi9e/cvvvgCLofHh7YlOzsbriY8xB6XAC' .
            'cSauJulD1sBKlhGpaL0++xVcQ/bNWq1d27d+t+zjWzFirjn9C7GD02UXq91KJvO8aPHz9x4sTk5GTbDxcvXnzo0KHychdpLJixhQsX' .
            'tmjRAnQGLsvZbsi7775LXBAACl20aNHJkydB1HZfYRi2b98+2OjUqROCPHiAoH7Tp08HfwHCclk4AfAcZ7JVAWZUzjTGYoK6Oxzllm' .
            '8W5pnqDJYEEUCdBw0aRFA4PJEsFgv/L8FucNagqoqKivpX/yFwItUsQ5JRGGmqbbbVM0zviNPSUBeqApzdZoxpvoLKWhlFISaiGRNq' .
            'BnaTY9/iEBcX9+abb4JkbU8cbNXatWuVStetuHBhf//9d7DccOtBXvhNr3sN3WrHWrFixZ49e5wlqODjwN+ZTA+bCebMmQMfulOyS5' .
            'Qs7Fd+yT22soBl3yf9J6fiY1G2s8Tn4MGDYGUJSs7LywMD3Lt378GDB58757hrcsuWLRMmTHDncrtDHvLgFDYJc9PdUBUxKhaWj7iY' .
            'HT9WG9lD90iUAjHM559/npiYaBstwYX68ccfMawes9Oqqqo2bNjQrVu39evXg9Tq7uA6xgJTDH6XeB/wd+AoX3vttdDQUHCCdvGT9+' .
            'znlYLZDzJxrFHFQW7ZF0KiWe3gskFbo0aNqvsVhKWrVq3avXu3VTFgk4YMGfLxxx+DZ8c/gaAKbsCHH5K52Av+EIOVhSCJlAIRNwa2' .
            'L1YmXtBVX+TIOkx/LqJTqz59+kAYaj1NMNhwjr/88otnvgUU+dlnn1VXV4MJhDDfNtN0YbHg+i5YsMAd8wNxPcSYUFGwbR5UkRhwds' .
            'v97t9EFRCqX0ZrZkpubxDlEDTS4NiFg1bAoEJcaGuHoNrg0CH5wv+E6/Xqq69ClEZW/XFCMW4RS/upMIesAqsJGxQYlszmJKfyT27l' .
            'TVT+98ULly9ftrUR8IDBaW7cuNHLiOWHH34Aq3/lyhXbtMaFsEDOkA25eQC4N2BgIXHwvI7OUbOwz4U5F9DqJeL0LLZbI0uTkpLqfi' .
            'iTyQ4fdtwgDnEGvgFPMGS+ZHlAK2BxvxcUYK6eB/fRMF04L/C5n4iy/+JWQ3J6JvUaPE7Lli0DJeHfQrQO2TopNQHPOGnSJIgurJ8Q' .
            'CQuE/PXX9ZjVf/z4cQjYwa1Yo3hyqWEZj/DcDaKhDg6FdeHCBWc/uXfvntrCp59+6mEVCepjZvqZ2RdRB+GIx1SyiCxWJqI6y6muq+' .
            'OLFy9u2rRp+/btVRbIqgyYKzxjw/8kEhY82Xx+PaaZQ/T31Vdfwe189tln3f+ViM15UhfQ1uAXinFQM1HQoGOaLnDcvTGQ+zi0nQTx' .
            'H3h8cOg///yzO/1oIJR4o6CDXtxNL43EXA/RgQwuna1Ss8hc0TkbIbLcR7kVBoaDw/39998QJKxZs4b0fmh4MmfNmoXH8kTBOxy+vo' .
            'o+cuTIunXrli5dCqJ0M4RXGvWvm5MEcmMuotnBLzrDra7XEZ2Bt9/W/bxt27YEv4LkyOGvbJGY2OPUkcN1oVoGVo7o+SZExjIUIFrw' .
            'OzKW07ZTkRnJdNVqBcla+/btIUV1s82sBCFKe6+gMsipHX4FEbc75XsApHq///77uHHjiCzW99/Xe2I7CHHnzp2QdPz6669jxowJD3' .
            'drBAtkE2wGKx4TzlDFJBqECKHdcgewVSkpKQ6/gnwb7p+zH8Idzc7OdvYty8zo1ix5rd8TA3W1DYOQokZj/CAzB2r+pD7wfwqnxQKB' .
            'GOc2m2gSae/evcGEzJ07d+HChVFRUQR7WlGxsBKWU21FmxpmqCNY/drWQWdfgxv2bGTFBx98AD4FDANsnD17FsLD1atXE+wvNCHM8g' .
            'dXPMDMmamK8eCgdnTv3r1dO8fvHpJKpT169PCs2HGayCW3/WPytA77UnhmlsjkNLisRAz3CNuuVq5cCf+Njo4eNWrUoUOHZsyY4U4c' .
            'InceZmUSOkrquH///q5duxwLC1KnHTt2eFYuhPxpaQ+W2YAkUSKRWFtNHMJhMI3YQw8SaOJ4P/gwMzMTjuvsW89G5Qaa0LGaSMTgNE' .
            'hKwIQELSDE4SNoKDIy0vonqB9StrVr14J9Ja6V2EmHoJJhJO5jdQn+ZMIddNN82gK5p+MYKyMjIzg4GG6Ps1+KRCKCbBzSddveQ4Je' .
            'W0DOxK6iMp4ZKUV0NUzjIV6ZywYql5SXl8fHO11X066PzE2SDSI9w0RwtzCGuZmRdx91bCcEhCM1Bg4c+NJLL+Xn50NOCjlQTEwM/B' .
            'fiLQhYFyxY4CzSDce4YU66dEQMNsfTiAICmBEjRvTv379Vq1aFhYUTJ060/bZnz54MSwZAUAKE146FBaG3bY+6HaDi4cOH//TTT852' .
            'uHz5ckVFhbWHknjoD+TDy8T3CXawJQTjqJiYyklMaqVDhw4EAzygPs46dwmASNnEMOcjmmCM49AVIgxmK6OfM2FxzUT2A+9vtQLpFc' .
            'PiU06fPg3R6qJFixzejilqohEcLYyiLHa9u0Dgum3cuLFLlweD22wdDj44pVmzZuCmnY26seLgbBUKBfh4gt9A3tSvXz+ChlCIf20f' .
            'snp1QhHTU++/pSZliTwBknyi3SxPlTPgYkGkXN9DZ7HVr0tvH+SWXUPlCmatDdY/msyDxYo1Oo6KwJKVOo+yCYC4AozH8uXLwZ7Zft' .
            '4iMWm5POkJPZGjVDM9adqYMGGCVVU4ELDiG3iabzab4+Lirl+/DmaVoBwHwioqKsrNJeqG69ixI5juN954g2Afays2g1RhPaULlprR' .
            'JwwBPOcGAB4s4sFY8FAOGDDAg6MXI7o9/NIV4vsv+1+dLrm1VpT5DT//JKdCy4AgEeJ2ZleDdJw60r9O3BNk4nQ0erjCp1wuB3v24Y' .
            'cfnjp1as+ePe+8887u3buPnjgOx/KsQAIgpOvVq5fdh3iuY80k8NbvwMDAli1bEhTlwF8cOXKE4AfgZaZOnQr3ZvLkyV988YUz93/8' .
            '+PF58+bh2wRxdL1oa/ALsNwzMBWXUZmz3Tp37gwhIHFRYN7hFIiDPwJMTEYeW5P3j6NBzMz2BvEAXWAPvf8QbQiITMYyFiJaI8MMDn' .
            'SUKHGssLnh/ec/nzPJs8PhFxnsBMMyPIlh6cNV81gmnZ5gmGgGUu8BFCqVynaUCs7o0aNBbRC2Pv300/CndQewL846xxgOhUXcOQgu' .
            'tk2b2gFrcG9Ats6EBVEChKK45UhKSgJtQaZJdE5ucJet/E5QMFMVW8UyEHSTQbwJlSQuCtKusLAwb8bu2VI7Ooojg38Qobcz+PXUBb' .
            'QxsP3MbHFcRELHFP29gtjtb3Hjw9t81cazvjmQkXV7586dEN3CbTZzy/w4SFuD+CVNuMOYrxpx0UVdF7izDhv5QkJCJBbgJsLlxT8M' .
            'DQ0lKMreocA5OBucBEDmOXv2bGuJzzzzjLM99Xr9J598gnebg7yIzaabwP07xCtf7pd+DZXVHdxnpXZsuCsgscANALmomdg5Ts2Hfl' .
            'nfCAvuosrAXinS/u3j9y0FVeEZn2fFWscBQ7A1f/58yI0gVgF7eQdV/iwomi9JBV9s9xMw6ipXXdR1gWvibBAb2BE8CbP2wAYHBxMU' .
            'ZS+sgwcPEgySGTlypO3w9iFDhhAUvWvXrkuXLuHbzkaweMAljmwH38VMHoIhsziQedi2G5FOJlv9pTBv2oUfvi6/xg6WwOP64osvuq' .
            'P4ukDsjAvr6tWrW7ZsqbsDZBWH6gzwKvcoVwD1EHRLbNq0ae7cuQsWLMD/JB5MZe8Kd+zYQdDHB8kgWEXrnwkJCcR5+5UrV/D1J8A3' .
            '1Te9J8DhOG5bXLZZw50m6BNkm5ldDJJzbnd4OwMMNphtMFSxsbGeud3w8HAIGQcPHgzbBCNNjnMqRmnDbD8hHvjgjNTUVHB2zh7L9h' .
            'asfyoURD1Uj1gsyAcJ/CCcJB5dWQGB9+/veKU8nJ9//hnfcNbBQgXgr112JJeUlBC0rpkY5ncUCSvlyZNV0fFGB2Pt6wWYGesQKJdA' .
            'zmV9KiBHW7169YwZM+BOV1ZW3rnjdKV7bZ2WBai2xOTJNDj3J3gRD1B4pJSTJ08SJEqQBYBHEwgEto/72LFjDxw4kJOT4/AnSqUSLk' .
            'fr1q1dTj+ExIqsEXDujKqAsK/u/CcrKKN2dEsngwT+9dcHrvC7n+5qYAIBGIa5Y67A9oNlgvgVri1cZEhswQNaAxpQm+0wOjvEdXJD' .
            'DdNEMNTCGXAI9zNlvBXXGY9U6M8/iZbaPXXqFN50Bk8VnDbEKODgeDxedTXRQJcTJ06AsKyNbA5Bzcxx6shoE7+GachG1Bc5NTqPrg' .
            'sOgWJsIe5it3bdaBgY1E3GNChYxlS2EgKaIsLBKh4AVn/EiBFwR1tacGgzoLYEc0PqTiH0rDcHks2ysjKXESoOcWzzyDmcPXuWYFdr' .
            '5yA8gjIL7oRNP/7446xZs6CukEQ4m8xkYJoNTJN1ltxMNaOCqf/AL9PZYinEqFSurQtcPoeTbHF0Fs+Siag2C/OsdWhm5K2Vt5SaUS' .
            'XTeA2V/8mtuIjKvLeyYKggKAYTRbzb/ftEvV51RxpyPe2BvnbtGlTJ5W579+4lnjH6UFgQaJOYu1kBLwCPIwQ0xJOxKliPxONBZg6E' .
            'ONdR+TpRprPRas6As7A2oTkDHpIr+MRrR0QZuVdR2WpRhu2h89ja5X73lykSQVu99QHwD/L5O2xFAaK9jsogV61XJa3gvc4u94FsnW' .
            'CHul3RRoaHij9+/PjQoUMJnjqGZWrhRx99RFzOQ11Tt2AS5MngRolV+zenGqyU7SfgjLoapN0J+wQdAq6Q+FyMVUr17vMEbZXhJh7E' .
            'VXUFfQ9VLhbfs3YRCs0I1HC0Nmxt5ICzW3YRDztzBjx4BKNIGBZVQQhr10ttRweDfb8t8YogBBw9etS2O64uYCBmzpxJMBwS56Gw9u' .
            '/f71lVSAGerwscB7Ea8fIEDjGZTHDaBK2Ree9tv3MvlSDGL2XpdU56cLPYmst1JkREb58fPbj7+PHjPZihBKEVccMp5IMrV64ksGr+' .
            'JjS0zmDRqxzPnc+SJUsIBg/Pnz//9GnXb+N+4AqvX7/uUoOUomJh+3hl7QziKFNtso0xzAZLj9sJTr1fuQa36vLly9u3b580aVLddo' .
            'eKP69d2r7vc3YGwXB/h3MQrBzmlXcz+Fsni/LaNee1qu1BUigUni10s2LFilWrVkHYDrbWtgQ4EUjpP/jggzNnzhD8/Fmtg64VlzPD' .
            'CIDjvvPOO6CeCRMmwFPK5XIhJ4Xg4a+//vrpp5/cmQbRsWPHB4uCbNmyhfix8AGImTlAF5hiEJsYZgiK77CVlzg1HueGUVFRnTt3Tk' .
            '5Ohic+MTHR398/MDAw7fKNsxt+vCYrKCScnA4xVgGbKCJsYRBO0ES1MogCeqeEr5nI7xBnMBjefvvtXbt2eVbbuLg4uIsJCQl9+/aF' .
            'orRaLQTRkKTv3r2b+EaGYJytNSlMBhOEDjm1wIzg+ew5tHqF81FudmtIkQs8HhCoPXg+wHE2rKoYlq7AI7wK92cOElNgwfonWALIvB' .
            '7EeYTeNc7IlzFdqPkeqlqMpglNSM+owM7n/7ix+caxY8eIsxNisrKyli9fzrA0ikI9S0pK3GxPGqgLYjNYuSw12NHLHJmCaRynjhxs' .
            'WV6L4Fd1hzCQyMCBA2NjY2stFvj41q1bezyGpKFgmRkEXdEeM04d8RO/iPSSqTASEhP7fXnyb7zSM9wq26AQno25yrhZUqct9RALdu' .
            'vWDeJ0cuvDsJwmuMtOnTrVms3U1FRiVYHLHDly5JNPPtmmTRtwMdbxVSKRqF4zWsllnrlFSIBbTXnuE4Fxu+v9SVcVeIfffvvNg1kJ' .
            'xDyjDYZn4Bivwi7VgAzjIK8MvKSzH4LlBgOJD+0iETC3cJp4sbWu8OLFi8R7111tJj8/H5Lktm3bpqenQ24PxXm5sER9gYfyKUNw60' .
            'UzJy2YRVaZXDPrXUVSOZOcBZhsAe8APmGeBbLK7KSXiM3sv5zM7z3BrRQQjrKH/GbHjh2Q3xBnBvVi1qxZcJr4du2xiYfgORw2Hx0d' .
            'DWEmhMM9evRYvHjx+fPnIa8k7rchEVDAG6pYs1rXNkM3btw4soodrA2KxvhlZPfYIAgyY8YM2Bg1ahRxn7379NL5/0+RkOF8cRSwYX' .
            '6EndBpaWmQ7q1du9bNScUu6devH+Qf1j9rhUUcttcdBF0XCI1BZL4ZwgCq+kjWsqWxdmxCxUe/LZv9Vr2WinAGOMFJlkkv3k8+s+PF' .
            'F1/ER5vAVYLsu0OHDl4WGGcUzFE15zGQK87HZwMDdERxAt6jCgbi119/9b5K48eP/+KLL2wnkNYKy25Whh3uzxsm3WfXxd+ErpAnxW' .
            'MP3ull1hlknx1cv369l9pKMgrXyVrijbGhzkMTDxg0aNCSJUusf6IoCjfSbsqN+4Dxe1EdDs8VPtS9t/NZOvD49dcFxRucdkFau7xg' .
            '4/vvv3/hhRc8qxLwyiuvzJkzx667s3YN0vj4eEgM6/adwWmsWbPGHYuFk5iY6GxBCzBmXgZhIhMyQR01TxUXbXokXdBczZQM6zr0lZ' .
            'eEQuGdO3fqm3mFY9x5yrgJ6kg/xoOm1DAT7y9OtcftZ7ZMnz79o48+spsIDlcV1JaUlARRjjv95dZfDRkyZM34N7rszWH/018Cz8BJ' .
            'bqXekYntoZc+rQ9h1fZnOBiu2KpVK7jv1sZYHo83YMAAEP21a9c8aBzQ6XTJycl2Q70fLG7bp08fCMPtutAhsps9e3a9jgHm7Y8//r' .
            'DrFsSfBm9a9p/SBS1WJHY2Sh1MRMZMigMXAycP6tr7icmTJ8MNuHHjhjtXBzEzn9EFL1TGJ2JCtk2xrNp5p6LLqMzltFgC4EGChGb4' .
            '8OHOdgBhvfrqqykpKXBXiPsKGRazt3XrVohgmDsvas6nWT+XmtEwExekY5fGCk3IGkULuFYJmPAuW2G3KE1oaOixY8fs5M5isSBEnj' .
            'ZtWkxMDDycBGO/GJbWCsY/EdSYMWPA4NWdQPBwOW5I9DZu3Hj27FkQMliprl27evZWOzB+3377bW5ubk1NTUhIyFNPPQWXLycn53//' .
            '+5/tQIs2bdq89957hYWFcFmL8gtK/7gU2CI2PS+7qqgUzHiwiRNgQiMwXnNMEG8U2K2QbgdLIghe8FzwvJH4n1WFJb+2n3DPLNMzTF' .
            'ITiq8xhFsgvOQojBeDCdoa/Pycz50yMkx32Eo5y3AzxY/bMtpgNOw74vrFOCKRCM4L0h0QjfuvfoCLc+rUKXjw4HYqFAp88nGLFi2Y' .
            'TCY4EwiAwsLCTCqtLqM4d+RKY6m9BbrDVvyfMLsCMaBmJjwVXfSS11Qx1j5WBdN4qZ0QndAbDgHFgqDBc7lcBL+kpASi+7KyssrKSq' .
            'gSPK6gNqgY3MrIyEi4xWB0cBe0dOnSqVOn1i3Bd6+VmzJlChgz659z5861zb1zRq3SXMkIfH1I2YqfPShc/ELPsBXj8RfXKo5eyx2x' .
            '0vsKA8L+7TgJYUGzht+sKoBLaZs+P//886Ae3PPCpQ8ICHD/PR0eUPDa57Jdfzl7jQrGMMM/MFEOV5eI3b9ENIDkN8jBueNDSOCyrF' .
            'ixou4OvnuXjlb7SPec3er+wj6tlX9c1WeWMFCEYai3D2JxUdAlLixdWqGXVWVY3lDH5KLBC0fzWkazgyVd4mvXyfjuu+8YluQOXC1k' .
            '1755By6guZFd8y3R65kQS18hw2bgqxVuiyjSVcWw+CV8w1mTgu/eV+jvXztAFJ7shQsXjhs3Du6T7bfioV2k4/oqj13nxnvSrCLfew' .
            'GTq8FfwLbZ644wTlyYeES36G/nivq0wV+7xbCZlAJZAsMyyMzLo7hP6ZJ6r4Bnhd/Nk6V1XGJ9qJyNffWdsPBprhB5zJgxA5JNuzGK' .
            '3KRIJocdNHeEzqO3f5kUmuqtR0wWT4GGuXgRiEsit84OWfyS4NFbYs03cVvr8ezT+lK8YJvymOevjTGWkLP0ph3/+c9/8EUcnHW9+0' .
            '5YkC2DE4H0wdncrIBpT0Nywm3lYna8MzRXMjN7vy3fd0HYy/WQbQKk4/sKuyVbDZUV6wRXcILNmzeHBMqbo7iJ8vRtL999b/L67XYO' .
            'AUOFO0Fn86F9+k5oyDKIF/ir2XkGIpv8Mes8PgQ7MjDxyvqckStt03L3QYLEidf/jx3gIKHbtm3bsmXLINOGoJV42QKyMOkMWX0XaW' .
            '94NQATbRYMF4SKV6/v2bMHshkIaRwOkPfpizBtZ1E7RDKyh/LMbb9hXRQHLnl2CGNhZdmaX7gJERDLe5AEIAF+kAc4/Orll18uLy9/' .
            '7rnnfKMqQLbzjJeqAngpzSl6of/IkSMJvm1cLxuHMIuJsiM3vgbPmceFVG7Ybyiqit72Zm2CWS84bNHA9s5uAxj/t99+OyHBRy+c1u' .
            'eWKQ6TkB/wO8aZ9Q0w0s6nrhAHzlOXVqDLKsEqFWYDZiyqFD/fk9829sHXRky29wKDzfLGIQKBb46QvtQ7e9ASiOtd7Gp5oTf8nyng' .
            'xh1fxU0Ip+gRdx/NtSzNtcySRd+6rrwT4BGF68wOlcb9+T4n1kcm1hafukJDUWXRm1sUB+yHf5Wv3xv99ZuS0Zb1cNmI39DO+qxSbt' .
            'tY3a0cj49VuX6vPqOYJeK5vDfSMX10aYWaS/dF/VMQibDBVaU4cg3qXLL4O49VBUR9P097Iydk4XOgMBLr5j4+slgQh1Z8uq/io9+c' .
            'XSyWHz/i02nSl/9ZKNuIyQ9fyXtxrTcHBX8aumKcsaRG/vslzYW0uh6BJRHwWjeL2jqHJeDCPqBsFo/TUHcCB9JAyOPKV+2AJNfjQs' .
            'Bah7//Com18gCfXEQjlvfCGuWx6wS7gOAKJn+KKbWBUx68FlVzPRt8kzfvgjfklVd+eTh66xzBEy3gESr/cLfi0GU8omeHB/g90yl8' .
            '7USrfQqY+JTHByIRk1xd+PomrMLzWYHiF3o1uKoYvrFYNT+fBtG4sydk+7VRTmLtOmO6+0X325Ew7FjYPyV06RhB1ySTSmsoqDTp9G' .
            'h4QN1mqsZA+Sd7Shd73sjOsISJyambGsPZ+SIrrHC7iQ+e1FqjYgHkFfHZjNrI2jtUJ25m9V2UPeRdrFrJTY7kpzRvDNf9EYxYzc4z' .
            'Gd3ne6kqIOKTqY3k7CgXFgQN2isZ7u9f/c3D3lYIegRPkLB4KaA6eSvzyUWQw5NSGolgNarskSsLJq7X3szxsijp+L7+E/qRUSkSoF' .
            'xYqtNOZ7c5BDwgXGt8G5EKw1aMA/9ISk2MxVU5w1aoL7v7FgyqgdMsXbnjXsJUsKnel8aODARz5X05ZEF58K6tZ5MBO8zfbHiQvhmK' .
            'Kg0l1QHTnq74cDcprXz6zOKsPv/ldUrwH9+3NgcUcBukcQFOqnTp9podZzzoG3AAigTPGxn81ugGbyixhXJhIRJhvfaXjn1Sde6e5N' .
            'luuS+u8bhjhxhwzcVXMso/2C0e3YMTExL0hicDZT1GfTE9b8w6MJ/kFIci8afX8ts1J6c08qBcWNwW9Zv+KxrQDuJr2AhfNxmTqdVn' .
            '6udJ3QdubZUlq1AeuRb01igRBHNsSl5ljQMWV77/YtW3x1RejIGpS9h7Exqhqhg+EBaEk2Xv73S/OQpiMlGf2rWZlUevU6cqW5THrs' .
            'M/TlyY9NX+/LaxaEwIr6WLIeHuA95cl1YI8YBs19+69EJvGtPrEjBtcNBsp/M1GhbKhQXZr2RUj5ofTrq5v8aSQkIuWTR7M4XVqoM+' .
            'q6Rs2Y/4tnhUj9ClL3OTPV9qwVgu01zLYiAsrEqhu51b9fUxb9o8HSIc2D5ivS/GhHmGLxpIIRGDkNndvRGW+NluqjN3SL8T9QNFxM' .
            'O6QoAv7NMaj/ExuVqXmo9GB6MhEnCa4NpsO38gxTPkl8M+7FCpoagK8RMwWEzFocvgZ9UejQwjxu/ZbtFbZzeqaN0OH/UVZvRaqL3q' .
            'eedXwwJa4TQPZfI46rOptckpwmKJBZzoIAaTCRsMDNOlFmDVStAZt2W0SalliXggQaoGqyCswFnDQ5eNcTZurJHgI2EVTNtQs/2kDw' .
            '702BPy7piQhc83dC1c46OBfuygRtHP0NQJnDWsSaiK4TNhScf3BYdCRcmiwR0FvVtTUXLjAkXC/29a+FoPX6Xpe3wkLEyuCf4vJY8a' .
            'Jyak+f6lQfNHUVF4I4Hlx2/2w1uBUwY3dEXqgY8GtaFRgSw+xzoImEQET7SAqDnsvfGCni0Lp33WwLkkBYBJjlg/FZ/k3YTwkcVCQ6' .
            'Ty/RfRyEByi2VyUb9BHfFt8dOdktM3P2ZuMXTlhNjfFjc5VTF8JiwwKphMRbqwIje9jkgf9kWyeJy435cFv/1cvefnND448eHRPy2w' .
            'LqHT5PDd9C9+Smxtqw95hH8y5eEYeStsJHTZ2OTUTf7/GdhE5YUEiSP+b3rSrc8kI3y0pisV+E5YooEdWAKnc6DrS8C0pwOnO33VOR' .
            'oRGLlhRuLFj/mdfDQNkCyk4/q2zPs64J+B/00X3wkLDfMX9UshpSiIZ8PWTnS5Gzc5Kv7P9yFM8Wb6q49AWML+Kc12/jfqK9JWF29Y' .
            'fDdhtbY3rawmo3391p50DIpIRvYInDVc0Nktg2TWGxVHrpV/uFtzMZ2Eo5OL5VxCFr/ITaLwrfq+x6fC0ueWZQ1Y7M2MLjvYoVK/Ed' .
            '2FPVv6DexgG8U7Q3Mju3jh174ZjeMSSGmlr/YPWfQCGurtukuNEJ9OsVf9nVq6dLv6HNFLqj0EYfHaxAh7tRINaCfsl0LUQWvElGdT' .
            '5bvPKQ5fMeQ5fpUw1fC7JvlPHCAe2qWRzKihAp8Ky1BUWfnZ7xXr7V+gQi6cFlEJZ9exeC6Wawf/qDp/D+pTd8o/RTAFXP9JTwW9Ma' .
            'wptkvVF18vCkLiyrMEsMMDor+bBy7SnZ0NJdUQe6n+vqs+e09zM5ucCQ7/wJII+B3ihX1aC59sW9vg0ohHUJGLz1ebMWL3u87T3XPw' .
            'kgFyQYLESTc/cyfwsgXMmPZunuZ6liG7VJdRDL5S4/6kSISFRgaiEQGchHBuYiS3RRQvJfbfYJwc0gDLGClP3855epkPDhQwc2jEus' .
            'leFnIvfoqxuHYZT+tCEiBZ/8kD2ZbZjohEiEgFSJCE0zzU+7VPHycaYGUVUZ82oiGdlQcvU32gqi8Phyx8zssA2Vj5YLHk2hWnLMIy' .
            'YyY8DyWhio8vDbOiX/S2OfxO8ZQfxoCpL3g13hw8IycuDN9mMh+8VwR8HOmdno8fDSMsRCyI/HKWD0YiGAq9mhcKVkqfWYxvY8oHM7' .
            'c40cH1Dd3+hTTYGqRMhBWz4+2wdZPJWprB8VG8XkXNmscJurdgWPJN8ajuaARtsVzQYKvX4T0YQTOHBk4dLD90uean08pj10lslMfx' .
            'PimDEE1vWaRE8ESLsBXjBN2S8fdf0BDTkMsi4oBRkYzoDv9MWr3y6PXq7/9UnbltkpHz3gdBtyQvSzD94wF5yVH4uyr+PW1R3tDwwr' .
            'LC4nHEw7vCvweNSZczdGkFmps5misZnlmy0FUTvBcB9o/EOcmRDCNG6foOjxONSFhWwIbx28fBP+snmhvZ1V8fq9lx2n1Lxm0bGzyX' .
            'hOGXiERgtMiayWLRqnKfBmgg9RiwZOpL6bJdf2nBjF3NJJhqjDYLjt4+X9A50fuD5j6/GoI/Yb+UZtvn007QfZqSsGwxVikqN+yv2n' .
            'rUbloO4i8KfHNE8JxnyVpV21BSjVUpeJ6+OupfS1MVFk7Nrr8KXv0E32aHBwRMeirwjWF0I1NjoHG9S6e+2M6u5iZHSsf3o1XVSGja' .
            'wtJnFFu3mQIuGhHQgJWhsaVpC4uT8PA9v2io1PuX9tKQRdMWlrB7C79nuzHQ2vc6Bcx4xuWoURqf0bSDdxyTSks3BDQ2Hgdh0TRCmr' .
            'YrpGm00MKioQRaWDSUQAuLhhJoYdFQAi0sGkqghUVDCbSwaCiBFhYNJdDCoqEEWlg0lEALi4YSaGHRUAItLBpKoIVFQwm0sGgogRYW' .
            'DSXQwqKhBFpYNJRAC4uGEmhh0VDC/wcAAP//ZLYUnavBJaIAAAAASUVORK5CYII=';

        $brands = [
            [
                'id'                        => 1,
                'name'                      => 'default',
                'company_name'              => 'AbuseIO',
                'introduction_text'         => 'This is an introduction text',
                'logo'                      => base64_decode($abuseio_base64)
            ],
        ];

        DB::table('brands')->insert($brands);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brands');
    }
}
