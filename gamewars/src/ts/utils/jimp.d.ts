declare interface Buffer {}


    declare type ImageCallback = (err: Error|null, image: JimpImage) => any;

    declare interface RGB {
        r: number;
        g: number;
        b: number;
    }
    
    declare interface RGBA {
        r: number;
        g: number;
        b: number;
        a: number;
    }

    declare interface JimpImage {
        bitmap: {data: Buffer, width: number, height: number};

        clone(cb?: ImageCallback): JimpImage;
        quality(n: number, cb?: ImageCallback): this;
        deflateLevel(l: number, cb?: ImageCallback): this;
        deflateStrategy(s: number, cb?: ImageCallback): this;
        filterType(f: number, cb?: ImageCallback): this;

        rgba(bool: boolean, cb?: ImageCallback): this;
        background(hex: number, cb?: ImageCallback): this;
        scan(x: number, y: number, w: number, h: number, f: (x:number, y:number, idx:number)=>any, cb?: ImageCallback): this;
        getMIME(): string;
        getExtension(): string;
        getPixelIndex(x: number, y: number, cb?: (err:Error, i:number)=>any): number;
        getPixelColor(x: number, y: number, cb?: (err:Error, hex:number)=>any): number;
        setPixelColor(hex: number, x: number, y: number, cb?: ImageCallback): this;
        hash(base?: number, cb?: (err:Error, hash: string)=>any): string;
        crop(x: number, y: number, w: number, h: number, cb?: ImageCallback): this;
        autocrop(tolerance?: number, cropOnlyFrames?: boolean, cb?: ImageCallback): this;
        blit(src: JimpImage, x: number, y: number, srcx?: number, srcy?: number, srcw?: number, srch?: number, cb?: ImageCallback): this;
        mask(src: JimpImage, x: number, y: number, cb?: ImageCallback): this
        composite(src: JimpImage, x: number, y: number, cb?: ImageCallback): this;
        brightness(val: number, cb?: ImageCallback): this;
        contrast(val: number, cb?: ImageCallback): this;
        posterize(n: number, cb?: ImageCallback): this;
        histogram(): {r: number[], g: number[], b: number[]};
        normalize(cb?: ImageCallback): this;
        invert(cb?: ImageCallback): this;
        mirror(horizontal: boolean, vertical: boolean, cb?: ImageCallback): this;
        gaussian(r: number, cb?: ImageCallback): this;
        blur(r: number, cb?: ImageCallback): this;

        greyscale(cb?: ImageCallback): this;
        grayscale(cb?: ImageCallback): this;
        sepia(cb?: ImageCallback): this;
        opacity(f: any, cb?: any):this;
        fade(f: any, cb?: any): this;
        opaque(cb: any): this;
        resize(w: number, h: number, mode?: string, cb?: ImageCallback): this;
        cover(w: number, h: number, alignBits?: number, mode?: string, cb?: ImageCallback): this;
        contain(w: number, h: number, alignBits?: number, mode?: string, cb?: ImageCallback): this;
        scale(f: number, mode?: string, cb?: ImageCallback): this;
        scaleToFit(w: number, h: number, mode?: any, cb?: ImageCallback): this;
        rotate(deg: number, mode?: number|boolean, cb?: ImageCallback): this;
        getBuffer(mime: string, cb:(err:Error, buffer:Buffer)=>any): this;

        write(path: string, cb?: ImageCallback): this;
    }

declare var Jimp: {

        // used to auto resizing etc.
        AUTO: number;

        // supported mime types
        MIME_PNG: string;
        MIME_JPEG: string;
        MIME_BMP: string;

        // PNG filter types
        PNG_FILTER_AUTO: number;
        PNG_FILTER_NONE: number;
        PNG_FILTER_SUB: number;
        PNG_FILTER_UP: number;
        PNG_FILTER_AVERAGE: number;
        PNG_FILTER_PAETH: number;

        // resize methods
        RESIZE_NEAREST_NEIGHBOR: string;
        RESIZE_BILINEAR: string;
        RESIZE_BICUBIC: string;
        RESIZE_HERMITE: string;
        RESIZE_BEZIER: string;

        // Align modes for cover, contain, bit masks
        HORIZONTAL_ALIGN_LEFT: number;
        HORIZONTAL_ALIGN_CENTER: number;
        HORIZONTAL_ALIGN_RIGHT: number;

        VERTICAL_ALIGN_TOP: number;
        VERTICAL_ALIGN_MIDDLE: number;
        VERTICAL_ALIGN_BOTTOM: number;

        // Font locations
        FONT_SANS_8_BLACK: string;
        FONT_SANS_16_BLACK: string;
        FONT_SANS_32_BLACK: string;
        FONT_SANS_64_BLACK: string;
        FONT_SANS_128_BLACK: string;

        FONT_SANS_8_WHITE: string;
        FONT_SANS_16_WHITE: string;
        FONT_SANS_32_WHITE: string;
        FONT_SANS_64_WHITE: string;
        FONT_SANS_128_WHITE: string;

        (path: string, cb?: ImageCallback): JimpImage;
        (image: JimpImage, cb?: ImageCallback): JimpImage;
        (data: Buffer, cb?: ImageCallback): JimpImage;
        (w: number, h: number, cb?: ImageCallback): JimpImage;

        read(src: string|Buffer, cb?: ImageCallback): Promise<JimpImage>;

        rgbaToInt(r: number, g: number, b: number, a: number, cb?: (err: Error, i: number)=>any): number;
        intToRgba(i: number, cb?: (err:Error, rgba: RGBA)=>any): RGBA;
        limit255(n: number): number;
        diff(img1: JimpImage, img2: JimpImage, threshold?: number): {percent: number, diff: JimpImage};
        distance(img1: JimpImage, img2: JimpImage): number;
}