import re
import csv

def remove_control_characters(html):

    def str_to_int(s, default, base=10):
        if int(s, base) < 0x10000:
            return unichr(int(s, base))
        return default

    outfile = open("HKALL_rmcc.csv", 'wb')
    with open(html, 'r') as f:
        reader = csv.reader(f)
        for row in reader:
            row[0] = re.sub(ur"&#(\d+);?", lambda c: str_to_int(c.group(1), c.group(0)), row[0])
            row[0] = re.sub(ur"&#[xX]([0-9a-fA-F]+);?", lambda c: str_to_int(c.group(1), c.group(0), base=16), row[0])
            row[0] = re.sub(ur"[\x00-\x08\x0b\x0e-\x1f\x7f]", "", row[0])
            row[1] = re.sub(ur"&#(\d+);?", lambda c: str_to_int(c.group(1), c.group(0)), row[1])
            row[1] = re.sub(ur"&#[xX]([0-9a-fA-F]+);?", lambda c: str_to_int(c.group(1), c.group(0), base=16), row[1])
            row[1] = re.sub(ur"[\x00-\x08\x0b\x0e-\x1f\x7f]", "", row[1])
            str = row[0] + "," + row[1] + "\n"
            outfile.write(str)

    outfile.close()
    f.close()


if __name__ == "__main__":
    data = "HKALL_tags_RT10count.csv"
    remove_control_characters(data)
